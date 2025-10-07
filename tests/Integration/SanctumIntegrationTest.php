<?php

declare(strict_types=1);

namespace Integration;

use App\Users\Infrastructure\Database\Eloquent\Models\PersonalAccessToken;
use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanctumIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Очистить БД перед каждым тестом
        $this->artisan('migrate:fresh');
    }

    public function test_can_create_and_authenticate_token()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $token = $user->createToken('test-token');
        $authenticatedUser = PersonalAccessToken::findToken($token->plainTextToken)?->tokenable;

        // Assert.env
        self::assertNotNull($authenticatedUser);
        self::assertEquals($user->id, $authenticatedUser->id);
        self::assertEquals(26, strlen($token->accessToken->id));
    }

    public function test_token_has_correct_ulid_format()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        self::assertMatchesRegularExpression('/^[0-9A-Za-z]{26}$/', $token->accessToken->id);
    }

    public function test_user_id_has_correct_ulid_format()
    {
        $user = User::factory()->create();

        self::assertMatchesRegularExpression('/^[0-9A-Za-z]{26}$/', $user->id);
    }

    public function test_can_revoke_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $token->accessToken->delete();

        $authenticatedUser = PersonalAccessToken::findToken($token->plainTextToken)?->tokenable;

        self::assertNull($authenticatedUser);
    }

    public function test_token_stored_in_correct_schema()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $storedToken = PersonalAccessToken::findToken($token->plainTextToken);

        self::assertNotNull($storedToken);
        self::assertEquals($user->id, $storedToken->tokenable_id);
    }
}
