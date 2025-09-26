<?php

declare(strict_types=1);

namespace Feature;

use App\Users\Domain\Contracts\UserRepository;
use App\Users\Domain\Entity\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @throws BindingResolutionException
     */
    public function test_user_repository_works(): void
    {
        $repository = $this->app->make(UserRepository::class);

        $user = new User(
            'John Doe',
            'test@example.com',
            'password',
            null
        );
        $savedUser = $repository->store($user);
        $this->assertInstanceOf(User::class, $savedUser);
        $this->assertEquals('John Doe', $savedUser->name);
        $this->assertNotNull($savedUser->ulid); // ULID должен быть сгенерирован
        $this->assertIsString($savedUser->ulid); // ULID должен быть строкой
    }
}
