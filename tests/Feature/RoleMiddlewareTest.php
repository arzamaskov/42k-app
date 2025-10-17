<?php

declare(strict_types=1);

namespace Feature;

use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_admin_role_can_access_admin_route()
    {
        // Создать пользователя с ролью admin
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Аутентифицировать пользователя
        $token = $user->createToken('test-token')->plainTextToken;

        // Сделать запрос к защищенному маршруту
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/admin/test');

        // Проверить, что получили 200
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Admin access granted',
        ]);
    }

    public function test_user_without_admin_role_cannot_access_admin_route()
    {
        // Создать пользователя с ролью user
        $user = User::factory()->create([
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        // Аутентифицировать пользователя
        $token = $user->createToken('test-token')->plainTextToken;

        // Сделать запрос к админскому маршруту
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/admin/test');

        // Проверить, что получили 403
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Недостаточно прав доступа',
            'errors' => [],
        ]);
    }

    public function test_user_with_coach_role_can_access_coach_route()
    {
        // Создать пользователя с ролью coach
        $user = User::factory()->create([
            'role' => 'coach',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/coach/test');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Coach access granted',
        ]);
    }

    public function test_user_with_default_role_cannot_access_admin_route()
    {
        // Создать пользователя без роли (должна быть 'user' по умолчанию)
        $user = User::factory()->create([
            // Не указываем role вообще - будет использован default 'user'
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/admin/test');

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Недостаточно прав доступа',
            'errors' => [],
        ]);
    }

    public function test_user_with_coach_role_cannot_access_admin_route()
    {
        // Создать пользователя с ролью coach
        $user = User::factory()->create([
            'role' => 'coach',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/admin/test');

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Недостаточно прав доступа',
            'errors' => [],
        ]);
    }
}
