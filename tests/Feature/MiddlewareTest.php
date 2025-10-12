<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    public function test_custom_auth_middleware_returns_proper_error_response()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'Необходима аутентификация',
            'errors' => [],
        ]);
    }
}
