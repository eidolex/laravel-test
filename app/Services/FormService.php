<?php

namespace App\Services;

use App\Events\FormSubmitted;
use App\Mail\SendFormMail;
use App\Models\Form;
use App\Models\FormInput;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\UnauthorizedException;

class FormService
{
    private Form $form;

    private FormInput $formInput;

    public function __construct(Form $form, FormInput $formInput)
    {
        $this->form = $form;
        $this->formInput = $formInput;
    }

    public function createNewForm(int $ownerId, array $data)
    {
        DB::beginTransaction();

        try {
            $form = $this->form->newInstance();

            $form->fill($data);
            $form->owner_id = $ownerId;
            $form->save();

            $this->createFormInputs($form->id, $data['fields']);

            $form->load('formInputs');

            DB::commit();

            return $form;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editForm(int $ownerId, int $formId, array $data)
    {
        $form = $this->getForm($formId);
        if ($form->owner_id != $ownerId) {
            throw new UnauthorizedException();
        }

        DB::beginTransaction();

        try {
            $this->deleteFormInputs($form->id);
            $form->fill($data);
            $form->save();

            $this->createFormInputs($form->id, $data['fields']);

            $form->load('formInputs');

            DB::commit();

            return $form;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteForm(int $ownerId, int $formId)
    {
        $form = $this->getForm($formId);

        if ($form->owner_id != $ownerId) {
            throw new UnauthorizedException();
        }

        DB::beginTransaction();

        try {
            $this->deleteFormInputs($formId);
            $form->delete();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getForm(int $formId, $loadFromInputs = false)
    {
        $form = $this->form->query()->findOrFail($formId);

        if ($loadFromInputs) {
            $form->load('formInputs');
        }

        return $form;
    }

    public function getFormBySlug(string $slug)
    {
        return $this->form->query()
            ->with(['formInputs'])
            ->where(['slug' => $slug])
            ->firstOrFail();
    }

    public function createFormInputs(int $formId, array $fields)
    {
        $now = now();
        $formInputs = [];

        foreach ($fields as $field) {
            $formInputs[] = [
                'name' => $field['name'],
                'label' => $field['label'],
                'input_type' => $field['input_type'],
                'attributes' => isset($field['attributes']) ? json_encode($field['attributes']) : null,
                'choices' => isset($field['choices']) ? json_encode($field['choices']) : null,
                'rules' => data_get($field, 'rules'),
                'input_order' => data_get($field, 'input_order', 0),
                'created_at' => $now,
                'updated_at' => $now,
                'form_id' => $formId,
            ];
        }

        $this->formInput->query()->insert($formInputs);
    }

    public function deleteFormInputs(int $formId): void
    {
        $this->formInput->query()
            ->where('form_id', $formId)
            ->delete();
    }

    public function submitForm(int $formId, array $data): void
    {
        // TODO: save data to db maybe

        $form = $this->getForm($formId, true);

        FormSubmitted::dispatch($formId, $data);

        try {
            Mail::to($form->owner->email)
                ->send(new SendFormMail($form, $data));
        } catch (Exception $e) {
            logger($e);
        }
    }

    public function generateRules(int $formId): array
    {
        $rules = [];

        $formInputs = $this->formInput->query()
            ->where('form_id', $formId)
            ->get();

        foreach ($formInputs as $formInput) {
            if ($formInput->rules) {
                $rules[$formInput->name] = $formInput->rules;
            } else {
                $rules[$formInput->name] = 'nullable';
            }
        }

        return $rules;
    }
}
