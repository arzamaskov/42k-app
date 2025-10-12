<?php

declare(strict_types=1);

namespace App\Users\UI\Http\Requests;

use App\Shared\UI\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:App\Users\Infrastructure\Database\Eloquent\Models\User',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не может быть длиннее 255 символов',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Email должен быть в правильном формате',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.confirmed' => 'Подтверждение пароля не совпадает',
        ];
    }
}
