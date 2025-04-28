<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|min:5|max:50',
            'content' => 'required|min:50|max:1000',
            'description' => 'required|min:10|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => "El titulo es requerido",
            'title.min' => "El minimo de caracteres son 5",
            'title.max' => "El maximo de caracteres son 50",
            'content.required' => "El contenido es requerido",
            'content.min' => "El minimo de contenido son 50 caracteres",
            'content.max' => "El maximo de caracteres son 1000",
            'description.required' => "La descripción es requerida",
            'description.min' => "El minimo de caracteres son 10",
            'description.max' => "El maximo de caracteres son 100"
        ];
    }
}
