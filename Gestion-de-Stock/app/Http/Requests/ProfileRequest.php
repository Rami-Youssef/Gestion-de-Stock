<?php

namespace App\Http\Requests;

use App\Models\Utilisateur;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'utilisateur' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique((new Utilisateur)->getTable())->ignore(auth()->id())],
        ];
    }
}
