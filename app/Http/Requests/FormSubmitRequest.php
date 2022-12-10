<?php

namespace App\Http\Requests;

use App\Services\FormService;
use Illuminate\Foundation\Http\FormRequest;

class FormSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(FormService $formService)
    {
        $formId = $this->post('form_id', null);

        $rules = $formService->generateRules($formId);

        return array_merge([
            'form_id' => 'required|integer|exists:forms,id',
        ], $rules);
    }
}
