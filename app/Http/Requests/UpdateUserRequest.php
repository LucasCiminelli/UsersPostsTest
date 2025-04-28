<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'password' => 'required|min:6'
        ];
    }

    public function messages(): array
    {

        return [
            'name.required' => "El nombre es requerido",
            'name.max' => "El maximo de caracteres es 256",
            'password.required' => "El password es requerido",
            'password.min' => "El password debe tener como minimo 6 caracteres"
        ];
    }
}
