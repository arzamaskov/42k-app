<?php

declare(strict_types=1);

namespace App\Users\Application\DTO;

use App\Users\Domain\Entities\User;
use App\Users\Infrastructure\Database\Eloquent\Models\User as EloquentUser;
use Carbon\Carbon;

final readonly class UserDTO
{
    private function __construct(
        private string $id,
        private string $name,
        private string $email,
        private Carbon $createdAt,
    ) {}

    public static function fromModel(EloquentUser $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            createdAt: $user->created_at
        );
    }

    public static function fromDomain(User $user): self
    {
        return new self(
            id: $user->getId()->toString(),
            name: $user->getName(),
            email: $user->getEmail()->toString(),
            createdAt: $user->getCreatedAt(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }
}
