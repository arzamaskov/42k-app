<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PasswordHash
{
    private function __construct(private string $hash)
    {
        if (trim($hash) === '') {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }
    }

    public static function fromString(string $hash): self
    {
        return new self($hash);
    }

    public function toString(): string
    {
        return $this->hash;
    }

    public function equals(self $other): bool
    {
        return $this->hash === $other->hash;
    }
}
