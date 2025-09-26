<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Database\Eloquent\Repositories;

use App\Shared\Infrastructure\Casting\Caster;
use App\Shared\Infrastructure\Casting\CastType;
use App\Users\Domain\Contracts\UserRepository;
use App\Users\Domain\Entity\User;
use App\Users\Infrastructure\Database\Eloquent\Models\UserModel;
use RuntimeException;

class EloquentUserRepository implements UserRepository
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function find(?string $ulid): ?User
    {
        if (null === $ulid) {
            return null;
        }

        $model = UserModel::query()->find($ulid);
        if (null === $model) {
            return null;
        }
        if (!$model instanceof UserModel) {
            throw new RuntimeException('Unexpected model type in EloquentUserRepository::find');
        }

        return $this->mapToEntity($model);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function findByEmail(string $email): ?User
    {
        $model = UserModel::query()->where('email', $email)->first();
        if (null === $model) {
            return null;
        }
        if (!$model instanceof UserModel) {
            throw new RuntimeException('Unexpected model type in EloquentUserRepository::findByEmail');
        }

        return $this->mapToEntity($model);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function store(User $user): User
    {
        if ($user->ulid !== null) {
            $model = UserModel::query()->find($user->ulid);
            if ($model === null) {
                throw new RuntimeException("User with id={$user->ulid} not found for update");
            }
        } else {
            $model = new UserModel();
        }

        if (!$model instanceof UserModel) {
            throw new RuntimeException('Unexpected model type in EloquentUserRepository::store');
        }

        $model->setAttribute('name', $user->name);
        $model->setAttribute('email', $user->email);
        $model->setAttribute('password', $user->password);
        $model->setAttribute('email_verified_at', $user->emailVerifiedAt);
        $model->setAttribute('remember_token', $user->rememberToken);

        $model->save();

        return $this->mapToEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $ulid): void
    {
        UserModel::query()->whereKey($ulid)->delete();
    }

    /**
     * @inheritDoc
     */
    public function existsByEmail(string $email): bool
    {
        return UserModel::query()->where('email', $email)->exists();
    }

    /**
     * @throws \Exception
     */
    private function mapToEntity(UserModel $model): User
    {
        return new User(
            (string) $model->getAttribute('name'),
            (string) $model->getAttribute('email'),
            (string) $model->getAttribute('password'),
            (string) $model->getKey(),
            Caster::cast($model->getAttribute('email_verified_at'), CastType::DateTimeImmutableNullable),
            $model->getAttribute('remember_token'),
            Caster::cast($model->getAttribute('created_at'), CastType::DateTimeImmutableNullable),
            Caster::cast($model->getAttribute('updated_at'), CastType::DateTimeImmutableNullable)
        );
    }
}
