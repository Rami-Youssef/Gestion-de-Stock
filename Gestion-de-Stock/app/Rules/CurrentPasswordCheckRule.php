<?php

namespace App\Rules;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class CurrentPasswordCheckRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, auth()->user()->motdepasse);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Le mot de passe actuel ne correspond pas à votre mot de passe');
    }
}
