<?php

declare(strict_types=1);

namespace App\Users\Domain\Entities;

use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\PasswordHash;
use App\Users\Domain\ValueObjects\Role;
use App\Users\Domain\ValueObjects\UserId;
use Carbon\Carbon;

final readonly class User
{
    private function __construct(
        private UserId $id,
        private string $name,
        private Email $email,
        private PasswordHash $passwordHash,
        private Role $role,
        private Carbon $createdAt,
    ) {}

    public static function create(
        UserId $id,
        string $name,
        Email $email,
        PasswordHash $passwordHash,
        ?Role $role = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            passwordHash: $passwordHash,
            role: $role ?? Role::user(),
            createdAt: Carbon::now(),
        );
    }

    public static function fromPersistence(
        UserId $id,
        string $name,
        Email $email,
        PasswordHash $passwordHash,
        Role $role,
        Carbon $createdAt,
    ): self {
        return new self($id, $name, $email, $passwordHash, $role, $createdAt);
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

    public function getRole(): Role
    {
        return $this->role;
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
            role: $this->role,
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
            role: $this->role,
            createdAt: $this->createdAt,
        );
    }

    public function changeRole(Role $newRole): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            email: $this->email,
            passwordHash: $this->passwordHash,
            role: $newRole,
            createdAt: $this->createdAt,
        );
    }

    public function isUser(): bool
    {
        return $this->role->isUser();
    }

    public function isCoach(): bool
    {
        return $this->role->isCoach();
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function isCoachOrAdmin(): bool
    {
        return $this->role->isAdminOrCoach();
    }
}
