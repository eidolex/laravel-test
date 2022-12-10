<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CheckRule implements InvokableRule
{

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $avaliableRules = config('rules');

        $invalids = [];
        $rules = array_map(
            fn ($str) => Str($str)->before(':')->toString(),
            explode("|", $value)
        );

        foreach ($rules as $rule) {
            if (array_search($rule, $avaliableRules) === false) {

                $invalids[] = $rule;
            }
        }


        if (count($invalids) > 0) {
            $fail('The :attribute contain invalid rules: ' . implode(" ,", $invalids));
        }
    }
}
