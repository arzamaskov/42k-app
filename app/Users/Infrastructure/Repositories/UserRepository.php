<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Repositories;

use App\Users\Domain\Repositories\UserRepositoryInterface;
use App\Users\Infrastructure\Database\Eloquent\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $user) {}

    public function create(array $data): User
    {
        return $this->user->newQuery()->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function find(string $id): ?User
    {
        return User::query()->find($id);
    }
}
