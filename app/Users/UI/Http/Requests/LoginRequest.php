<?php

declare(strict_types=1);

namespace App\Users\UI\Http\Requests;

use App\Shared\UI\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Email должен быть в правильном формате',
            'password.required' => 'Пароль обязателен для заполнения',
        ];
    }
}
