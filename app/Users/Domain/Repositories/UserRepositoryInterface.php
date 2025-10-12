<?php

declare(strict_types=1);

namespace App\Users\Domain\Repositories;

use App\Users\Infrastructure\Database\Eloquent\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function find(string $id): ?User;
}
