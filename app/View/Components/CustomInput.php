<?php

namespace App\View\Components;

use App\Enums\InputType;
use App\Models\FormInput;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CustomInput extends Component
{
    public FormInput $formInput;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(FormInput $formInput)
    {
        $this->formInput = $formInput;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        $inputName = $this->formInput->name;

        if ($this->formInput->input_type == InputType::Checkbox->value) {
            $inputName .= "[]";
        }

        return view('components.custom-input', [
            'inputId' => Str($this->formInput->name)
                ->prepend('id_'),
            'inputName' => $inputName
        ]);
    }
}
