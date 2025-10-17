<?php

declare(strict_types=1);

namespace Feature\Users;

use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_get_own_profile()
    {
        // Сначала создаем пользователя и получаем токен
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/профиль');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ],
            ],
        ]);

        // Проверяем, что возвращаются данные текущего пользователя
        $response->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);

        // Проверяем, что пароль не возвращается
        $response->assertJsonMissing(['password']);
    }

    public function test_user_cannot_get_profile_without_token()
    {
        $response = $this->getJson('/api/профиль');

        $response->assertStatus(401);
    }

    public function test_user_cannot_get_profile_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/профиль');

        $response->assertStatus(401);
    }
}
