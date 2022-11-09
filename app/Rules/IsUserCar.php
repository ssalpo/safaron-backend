<?php

namespace App\Rules;

use App\Models\Car;
use Illuminate\Contracts\Validation\InvokableRule;

class IsUserCar implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $existsCar = Car::whereId($value)->whereUserId(auth()->id())->exists();

        if (!$existsCar) {
            $fail('The :attribute must be correct car id that related to current user.');
        }
    }
}
