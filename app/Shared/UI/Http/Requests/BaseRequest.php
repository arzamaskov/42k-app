<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validatedData(): array
    {
        return $this->validated();
    }

    public function failedValidation(Validator $validator): void
    {
        Log::debug('Validation failed', [
            'request_class' => static::class,
            'request_data' => $this->all(),
            'validation_errors' => $validator->errors()->toArray(),
        ]);

        parent::failedValidation($validator);
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения',
            'email' => 'Поле :attribute должно быть корректным email адресом',
            'min' => 'Поле :attribute должно содержать минимум :min символов',
            'max' => 'Поле :attribute не может быть длиннее :max символов',
            'confirmed' => 'Подтверждение :attribute не совпадает',
            'unique' => 'Пользователь с таким :attribute уже существует',
        ];
    }
}
