<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Role
{
    public const string ROLE_ADMIN = 'admin';

    public const string ROLE_USER = 'user';

    public const string ROLE_COACH = 'coach';

    public const array VALID_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
        self::ROLE_COACH,
    ];

    private function __construct(private string $role)
    {
        if (! in_array($role, self::VALID_ROLES, true)) {
            throw new InvalidArgumentException("Invalid role: $role");
        }
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public static function coach(): self
    {
        return new self(self::ROLE_COACH);
    }

    public static function fromString(string $role): self
    {
        return new self($role);
    }

    public function toString(): string
    {
        return $this->role;
    }

    public function equals(self $other): bool
    {
        return $this->role === $other->role;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCoach(): bool
    {
        return $this->role === self::ROLE_COACH;
    }

    public function isAdminOrCoach(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_COACH;
    }

    public static function getAllValidRoles(): array
    {
        return self::VALID_ROLES;
    }
}
