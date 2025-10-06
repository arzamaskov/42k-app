<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class UserId
{
    private function __construct(private string $value)
    {
        if (! Str::isUlid($this->value)) {
            throw new InvalidArgumentException(
                "Invalid ULID format: {$this->value}"
            );
        }
    }

    public static function generate(): self
    {
        return new self((string) Str::ulid());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
