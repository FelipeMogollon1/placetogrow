<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }


    public function rules(): array
    {
        return [
            "name" => "required|string|unique:categories,name",
            "description" => "nullable|string",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "El nombre de la categoría es requerido.",
            "name.string" => "El nombre de la categoría debe ser una cadena de caracteres.",
            "name.unique" => "El nombre de la categoría ya existe. Por favor, elige otro.",
            "description.string" => "La descripción debe ser una cadena de caracteres.",
        ];

    }
}
