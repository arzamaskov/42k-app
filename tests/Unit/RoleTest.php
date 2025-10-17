<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Users\Domain\ValueObjects\Role;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function test_can_create_user_role()
    {
        $role = Role::user();

        $this->assertTrue($role->isUser());
        $this->assertFalse($role->isAdmin());
        $this->assertFalse($role->isCoach());
        $this->assertFalse($role->isAdminOrCoach());
        $this->assertEquals('user', $role->toString());
    }

    public function test_can_create_coach_role()
    {
        $role = Role::coach();

        $this->assertTrue($role->isCoach());
        $this->assertFalse($role->isUser());
        $this->assertFalse($role->isAdmin());
        $this->assertTrue($role->isAdminOrCoach());
        $this->assertEquals('coach', $role->toString());
    }

    public function test_can_create_admin_role()
    {
        $role = Role::admin();

        $this->assertTrue($role->isAdmin());
        $this->assertFalse($role->isUser());
        $this->assertFalse($role->isCoach());
        $this->assertTrue($role->isAdminOrCoach());
        $this->assertEquals('admin', $role->toString());
    }

    public function test_can_create_role_from_string()
    {
        $userRole = Role::fromString('user');
        $coachRole = Role::fromString('coach');
        $adminRole = Role::fromString('admin');

        $this->assertTrue($userRole->isUser());
        $this->assertTrue($coachRole->isCoach());
        $this->assertTrue($adminRole->isAdmin());
    }

    public function test_throws_exception_for_invalid_role()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid role: invalid_role');

        Role::fromString('invalid_role');
    }

    public function test_equals_compares_values()
    {
        $role1 = Role::user();
        $role2 = Role::user();
        $role3 = Role::admin();

        $this->assertTrue($role1->equals($role2));
        $this->assertFalse($role1->equals($role3));
    }

    public function test_get_all_valid_roles()
    {
        $validRoles = Role::getAllValidRoles();

        $this->assertContains('user', $validRoles);
        $this->assertContains('coach', $validRoles);
        $this->assertContains('admin', $validRoles);
        $this->assertCount(3, $validRoles);
    }
}
