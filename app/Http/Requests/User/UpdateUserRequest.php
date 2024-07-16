<?php

namespace App\Http\Requests\User;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed'],
        ];
    }
}
