<?php

declare(strict_types=1);

namespace App\Users\Domain\Contracts;

use App\Users\Domain\Entity\User;

interface UserRepository
{
    /**
     * Найти пользователя по ID
     */
    public function find(?string $ulid): ?User;

    /**
     * Найти пользователя по email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Сохранить пользователя (создать или обновить)
     */
    public function store(User $user): User;

    /**
     * Удалить пользователя по ID
     */
    public function delete(string $ulid): void;

    /**
     * Проверить существование пользователя с указанным email
     */
    public function existsByEmail(string $email): bool;
}
