<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:256',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ];
    }


    public function messages(): array
    {

        return [
            'name.required' => "El nombre es requerido",
            'name.max' => "El maximo de caracteres es 256",
            'email.required' => "El email es requerido",
            'email.email' => "El formato debe ser email valido",
            'email.unique' => "El email ya existe en la base de datos",
            'password.required' => "El password es requerido",
            'password.min' => "El password debe tener como minimo 6 caracteres"
        ];
    }
}
