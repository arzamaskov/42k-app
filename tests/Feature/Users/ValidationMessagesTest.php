<?php

declare(strict_types=1);

namespace Feature\Users;

use Tests\TestCase;

class ValidationMessagesTest extends TestCase
{
    public function test_register_validation_returns_russian_messages()
    {
        $response = $this->postJson('/api/регистрация', [
            'name' => '', // Пустое имя
            'email' => 'invalid-email', // Неверный email
            'password' => '123', // Короткий пароль
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);

        // Проверяем конкретные русские сообщения
        $response->assertJsonFragment([
            'name' => ['Имя обязательно для заполнения'],
            'email' => ['Email должен быть в правильном формате'],
            'password' => ['Пароль должен содержать минимум 8 символов', 'Подтверждение пароля не совпадает'],
        ]);
    }

    public function test_login_validation_returns_russian_messages()
    {
        $response = $this->postJson('/api/вход', [
            'email' => 'invalid-email', // Неверный email
            'password' => '', // Пустой пароль
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);

        // Проверяем конкретные русские сообщения
        $response->assertJsonFragment([
            'email' => ['Email должен быть в правильном формате'],
            'password' => ['Пароль обязателен для заполнения'],
        ]);
    }
}
