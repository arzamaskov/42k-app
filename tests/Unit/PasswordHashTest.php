<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Users\Domain\ValueObjects\PasswordHash;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PasswordHashTest extends TestCase
{
    public function test_can_create_password_hash_from_string()
    {
        $hash = 'hashed_password_string';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_create_password_hash_with_special_characters()
    {
        $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_create_password_hash_with_numbers()
    {
        $hash = 'hash123456789';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_create_password_hash_with_very_long_string()
    {
        $hash = str_repeat('a', 1000);
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_throws_exception_for_empty_hash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password hash cannot be empty');

        PasswordHash::fromString('');
    }

    public function test_throws_exception_for_whitespace_only_hash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password hash cannot be empty');

        PasswordHash::fromString('   ');
    }

    public function test_throws_exception_for_tab_only_hash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password hash cannot be empty');

        PasswordHash::fromString("\t");
    }

    public function test_throws_exception_for_newline_only_hash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password hash cannot be empty');

        PasswordHash::fromString("\n");
    }

    public function test_equals_compares_values()
    {
        $hash1 = 'same_hash_value';
        $hash2 = 'same_hash_value';
        $hash3 = 'different_hash_value';

        $passwordHash1 = PasswordHash::fromString($hash1);
        $passwordHash2 = PasswordHash::fromString($hash2);
        $passwordHash3 = PasswordHash::fromString($hash3);

        $this->assertTrue($passwordHash1->equals($passwordHash2));
        $this->assertFalse($passwordHash1->equals($passwordHash3));
    }

    public function test_equals_is_case_sensitive()
    {
        $passwordHash1 = PasswordHash::fromString('HashValue');
        $passwordHash2 = PasswordHash::fromString('hashvalue');

        $this->assertFalse($passwordHash1->equals($passwordHash2));
    }

    public function test_can_handle_hash_with_leading_whitespace()
    {
        $hash = '  valid_hash';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_handle_hash_with_trailing_whitespace()
    {
        $hash = 'valid_hash  ';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_handle_hash_with_mixed_whitespace()
    {
        $hash = "  valid\t\nhash  ";
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_handle_unicode_characters()
    {
        $hash = 'хеш_с_русскими_символами';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }

    public function test_can_handle_single_character_hash()
    {
        $hash = 'a';
        $passwordHash = PasswordHash::fromString($hash);

        $this->assertEquals($hash, $passwordHash->toString());
    }
}
