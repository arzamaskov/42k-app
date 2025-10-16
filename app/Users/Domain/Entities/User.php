<?php

declare(strict_types=1);

namespace App\Users\Domain\Entities;

use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\PasswordHash;
use App\Users\Domain\ValueObjects\UserId;
use Carbon\Carbon;

final readonly class User
{
    private function __construct(
        private UserId $id,
        private string $name,
        private Email $email,
        private PasswordHash $passwordHash,
        private Carbon $createdAt,
    ) {}

    public static function create(
        UserId $id,
        string $name,
        Email $email,
        PasswordHash $passwordHash,
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            passwordHash: $passwordHash,
            createdAt: Carbon::now(),
        );
    }

    public static function fromPersistence(
        UserId $id,
        string $name,
        Email $email,
        PasswordHash $passwordHash,
        Carbon $createdAt,
    ): self {
        return new self($id, $name, $email, $passwordHash, $createdAt);
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function changePassword(PasswordHash $newPasswordHash): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            email: $this->email,
            passwordHash: $newPasswordHash,
            createdAt: $this->createdAt,
        );
    }

    public function updateProfile(string $name): self
    {
        return new self(
            id: $this->id,
            name: $name,
            email: $this->email,
            passwordHash: $this->passwordHash,
            createdAt: $this->createdAt,
        );
    }
}
