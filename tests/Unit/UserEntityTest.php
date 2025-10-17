<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Users\Domain\Entities\User;
use App\Users\Domain\ValueObjects\Email;
use App\Users\Domain\ValueObjects\PasswordHash;
use App\Users\Domain\ValueObjects\Role;
use App\Users\Domain\ValueObjects\UserId;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    private UserId $userId;

    private string $name;

    private Email $email;

    private PasswordHash $passwordHash;

    private Carbon $createdAt;

    protected function setUp(): void
    {
        $this->userId = UserId::generate();
        $this->name = 'Test User';
        $this->email = Email::fromString('test@example.com');
        $this->passwordHash = PasswordHash::fromString('hashed_password');
        $this->createdAt = Carbon::now();
    }

    public function test_can_create_user_with_default_role()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash
        );

        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isCoach());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isCoachOrAdmin());
        $this->assertEquals($this->userId, $user->getId());
        $this->assertEquals($this->name, $user->getName());
        $this->assertEquals($this->email, $user->getEmail());
        $this->assertEquals($this->passwordHash, $user->getPasswordHash());
    }

    public function test_can_create_user_with_specific_role()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::admin()
        );

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isUser());
        $this->assertFalse($user->isCoach());
        $this->assertTrue($user->isCoachOrAdmin());
    }

    public function test_can_create_user_from_persistence()
    {
        $user = User::fromPersistence(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::coach(),
            $this->createdAt
        );

        $this->assertTrue($user->isCoach());
        $this->assertFalse($user->isUser());
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isCoachOrAdmin());
        $this->assertEquals($this->createdAt, $user->getCreatedAt());
    }

    public function test_can_change_password()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash
        );

        $newPasswordHash = PasswordHash::fromString('new_hashed_password');
        $updatedUser = $user->changePassword($newPasswordHash);

        $this->assertEquals($newPasswordHash, $updatedUser->getPasswordHash());
        $this->assertEquals($this->name, $updatedUser->getName());
        $this->assertEquals($this->email, $updatedUser->getEmail());
        $this->assertEquals($this->userId, $updatedUser->getId());
        $this->assertTrue($updatedUser->isUser());
    }

    public function test_can_update_profile()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash
        );

        $newName = 'Updated Name';
        $updatedUser = $user->updateProfile($newName);

        $this->assertEquals($newName, $updatedUser->getName());
        $this->assertEquals($this->email, $updatedUser->getEmail());
        $this->assertEquals($this->passwordHash, $updatedUser->getPasswordHash());
        $this->assertEquals($this->userId, $updatedUser->getId());
        $this->assertTrue($updatedUser->isUser());
    }

    public function test_can_change_role()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::user()
        );

        $this->assertTrue($user->isUser());

        $updatedUser = $user->changeRole(Role::admin());

        $this->assertTrue($updatedUser->isAdmin());
        $this->assertFalse($updatedUser->isUser());
        $this->assertTrue($updatedUser->isCoachOrAdmin());
        $this->assertEquals($this->name, $updatedUser->getName());
        $this->assertEquals($this->email, $updatedUser->getEmail());
        $this->assertEquals($this->passwordHash, $updatedUser->getPasswordHash());
        $this->assertEquals($this->userId, $updatedUser->getId());
    }

    public function test_can_change_role_from_user_to_coach()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::user()
        );

        $updatedUser = $user->changeRole(Role::coach());

        $this->assertTrue($updatedUser->isCoach());
        $this->assertFalse($updatedUser->isUser());
        $this->assertFalse($updatedUser->isAdmin());
        $this->assertTrue($updatedUser->isCoachOrAdmin());
    }

    public function test_can_change_role_from_coach_to_admin()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::coach()
        );

        $updatedUser = $user->changeRole(Role::admin());

        $this->assertTrue($updatedUser->isAdmin());
        $this->assertFalse($updatedUser->isUser());
        $this->assertFalse($updatedUser->isCoach());
        $this->assertTrue($updatedUser->isCoachOrAdmin());
    }

    public function test_user_is_immutable()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::user()
        );

        $updatedUser = $user->changeRole(Role::admin());

        // Original user should remain unchanged
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());

        // Updated user should have new role
        $this->assertTrue($updatedUser->isAdmin());
        $this->assertFalse($updatedUser->isUser());
    }

    public function test_get_role_returns_role_value_object()
    {
        $user = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::coach()
        );

        $role = $user->getRole();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertTrue($role->isCoach());
    }

    public function test_all_role_check_methods_work_correctly()
    {
        $userRole = User::create(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::user()
        );

        $coachRole = User::create(
            UserId::generate(),
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::coach()
        );

        $adminRole = User::create(
            UserId::generate(),
            $this->name,
            $this->email,
            $this->passwordHash,
            Role::admin()
        );

        // User role checks
        $this->assertTrue($userRole->isUser());
        $this->assertFalse($userRole->isCoach());
        $this->assertFalse($userRole->isAdmin());
        $this->assertFalse($userRole->isCoachOrAdmin());

        // Coach role checks
        $this->assertFalse($coachRole->isUser());
        $this->assertTrue($coachRole->isCoach());
        $this->assertFalse($coachRole->isAdmin());
        $this->assertTrue($coachRole->isCoachOrAdmin());

        // Admin role checks
        $this->assertFalse($adminRole->isUser());
        $this->assertFalse($adminRole->isCoach());
        $this->assertTrue($adminRole->isAdmin());
        $this->assertTrue($adminRole->isCoachOrAdmin());
    }
}
