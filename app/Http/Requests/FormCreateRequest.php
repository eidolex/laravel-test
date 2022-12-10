<?php

namespace App\Http\Requests;

use App\Enums\InputType;
use App\Rules\CheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class FormCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $id = $this->route('form');

        return [
            'title' => 'required',
            'slug' => 'required|unique:forms,slug,' . $id,
            'fields' => 'required|array',
            'fields.*.name' => 'required|max:100',
            'fields.*.label' => 'required|max:255',
            'fields.*.input_type' => [
                'required',
                new Enum(InputType::class),
            ],
            'fields.*.choices' => [
                'required_if:fields.*.input_type,' . InputType::Checkbox->value . ',' . InputType::Radio->value . ',' . InputType::Select->value,
                'array',

            ],
            'fields.*.choices.*.label' => 'required',
            'fields.*.choices.*.value' => 'required',
            'fields.*.rules' => ['nullable', 'string', new CheckRule],
            'fields.*.attributes' => 'nullable|array',
            'fields.*.attributes.*' => 'nullable|string',
            'fields.*.input_order' => 'nullable|integer',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }
}
