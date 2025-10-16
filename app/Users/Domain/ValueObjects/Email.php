<?php

declare(strict_types=1);

namespace App\Users\Domain\ValueObjects;

use PharIo\Manifest\InvalidEmailException;

final readonly class Email
{
    private function __construct(private string $email)
    {
        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Invalid email format {$this->email}");
        }
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }
}
