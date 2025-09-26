<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

final readonly class User
{
    public function __construct(
        public string              $name,
        public string              $email,
        public string              $password,
        public ?string             $ulid = null,
        public ?\DateTimeImmutable $emailVerifiedAt = null,
        public ?string             $rememberToken = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt  = null,
    ) {
    }

    /**
     * Проверяет, подтвержден ли email пользователя
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    /**
     * Возвращает инициалы пользователя
     */
    public function getInitials(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';

        foreach ($words as $word) {
            if ($word !== '') {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
        }

        return $initials !== '' ? $initials : 'U';
    }
}
