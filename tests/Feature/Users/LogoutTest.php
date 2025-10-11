<?php

declare(strict_types=1);

namespace Feature\Users;

use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_user_can_logout_successfully()
    {
        // Сначала создаем пользователя и получаем токен
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Successfully logged out'
        ]);
    }

    public function test_user_cannot_logout_without_token()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
