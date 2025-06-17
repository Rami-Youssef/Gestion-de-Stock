<?php

namespace App\Http\Requests;

use App\Models\Utilisateur;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'utilisateur' => [
                'required', 'min:3'
            ],
            'email' => [
                'required', 'email', Rule::unique((new Utilisateur)->getTable())->ignore($this->route()->user->id ?? null)
            ],
            'password' => [
                $this->route()->user ? 'nullable' : 'required', 'confirmed', 'min:6'
            ],
            'role' => [
                'required', Rule::in(['admin', 'utilisateur'])
            ]
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'utilisateur' => 'nom d\'utilisateur',
            'email' => 'adresse email',
            'password' => 'mot de passe',
            'role' => 'rôle'
        ];
    }
}
