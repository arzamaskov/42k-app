<?php

declare(strict_types=1);

namespace App\Users\Domain\Repositories;

use App\Users\Domain\Entities\User;
use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findByEmail(Email $email): ?User;

    public function findById(UserId $id): ?User;
}
