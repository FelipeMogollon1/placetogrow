<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255|unique:roles,name',
           'permissions' => 'required|array',
           'permissions.*' => 'exists:permissions,name',
        ];
    }
}
