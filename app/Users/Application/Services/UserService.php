<?php

declare(strict_types=1);

namespace App\Users\Application\Services;

use App\Users\Application\DTO\CreateUserDTO;
use App\Users\Application\DTO\UserDTO;
use App\Users\Domain\Entities\User;
use App\Users\Domain\Repositories\UserRepositoryInterface;
use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\PasswordHash;
use App\Users\Domain\ValueObjects\UserId;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function createUser(CreateUserDTO $createUserDTO): UserDTO
    {
        $user = User::create(
            id: UserId::generate(),
            name: $createUserDTO->getName(),
            email: Email::fromString($createUserDTO->getEmail()),
            passwordHash: PasswordHash::fromString(Hash::make($createUserDTO->getPassword())),
        );

        $savedUser = $this->userRepository->save($user);

        return UserDTO::fromDomain($savedUser);
    }

    public function findByEmail(string $email): ?UserDTO
    {
        $user = $this->userRepository->findByEmail(
            Email::fromString($email)
        );

        return $user ? UserDTO::fromDomain($user) : null;
    }

    public function findById(string $id): ?UserDTO
    {
        $user = $this->userRepository->findById(
            UserId::fromString($id)
        );

        return $user ? UserDTO::fromDomain($user) : null;
    }
}
