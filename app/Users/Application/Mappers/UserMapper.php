<?php

declare(strict_types=1);

namespace App\Users\Application\Mappers;

use App\Users\Domain\Entities\User;
use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\PasswordHash;
use App\Users\Domain\ValueObjects\Role;
use App\Users\Domain\ValueObjects\UserId;
use App\Users\Infrastructure\Database\Eloquent\Models\User as EloquentUser;

final class UserMapper
{
    public static function toDomain(EloquentUser $eloquentUser): User
    {
        return User::fromPersistence(
            id: UserId::fromString($eloquentUser->id),
            name: $eloquentUser->name,
            email: Email::fromString($eloquentUser->email),
            passwordHash: PasswordHash::fromString($eloquentUser->password),
            role: Role::fromString($eloquentUser->role ?? 'user'),
            createdAt: $eloquentUser->created_at,
        );
    }

    public static function toEloquent(User $user): EloquentUser
    {
        return new EloquentUser([
            'id' => $user->getId()->toString(),
            'name' => $user->getName(),
            'email' => $user->getEmail()->toString(),
            'password' => $user->getPasswordHash()->toString(),
            'role' => $user->getRole()->toString(),
            'created_at' => $user->getCreatedAt(),
        ]);
    }
}
