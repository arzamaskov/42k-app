<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Repositories;

use App\Users\Application\Mappers\UserMapper;
use App\Users\Domain\Entities\User;
use App\Users\Domain\Repositories\UserRepositoryInterface;
use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\UserId;
use App\Users\Infrastructure\Database\Eloquent\Models\User as EloquentUser;

readonly class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::query()->where('email', $email->toString())->first();

        return $eloquentUser ? UserMapper::toDomain($eloquentUser) : null;
    }

    public function findById(UserId $id): ?User
    {
        $eloquentUser = EloquentUser::query()->find($id->toString());

        return $eloquentUser ? UserMapper::toDomain($eloquentUser) : null;
    }

    public function save(User $user): User
    {
        $eloquentUser = UserMapper::toEloquent($user);
        $eloquentUser->save();

        return UserMapper::toDomain($eloquentUser);
    }
}
