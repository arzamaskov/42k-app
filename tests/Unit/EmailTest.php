<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Users\Domain\ValueObjects\Email;
use PharIo\Manifest\InvalidEmailException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_can_create_email_from_valid_string()
    {
        $email = Email::fromString('test@example.com');

        $this->assertEquals('test@example.com', $email->toString());
    }

    public function test_can_create_email_with_plus_sign()
    {
        $email = Email::fromString('user+tag@example.com');

        $this->assertEquals('user+tag@example.com', $email->toString());
    }

    public function test_can_create_email_with_subdomain()
    {
        $email = Email::fromString('user@sub.example.com');

        $this->assertEquals('user@sub.example.com', $email->toString());
    }

    public function test_can_create_email_with_numbers()
    {
        $email = Email::fromString('user123@example123.com');

        $this->assertEquals('user123@example123.com', $email->toString());
    }

    public function test_throws_exception_for_invalid_email_format()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format invalid-email');

        Email::fromString('invalid-email');
    }

    public function test_throws_exception_for_email_without_at()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format userexample.com');

        Email::fromString('userexample.com');
    }

    public function test_throws_exception_for_email_without_domain()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format user@');

        Email::fromString('user@');
    }

    public function test_throws_exception_for_email_without_username()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format @example.com');

        Email::fromString('@example.com');
    }

    public function test_throws_exception_for_empty_email()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format ');

        Email::fromString('');
    }

    public function test_throws_exception_for_whitespace_only_email()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format    ');

        Email::fromString('   ');
    }

    public function test_equals_compares_values()
    {
        $email1 = Email::fromString('test@example.com');
        $email2 = Email::fromString('test@example.com');
        $email3 = Email::fromString('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    public function test_equals_is_case_sensitive()
    {
        $email1 = Email::fromString('Test@Example.com');
        $email2 = Email::fromString('test@example.com');

        $this->assertFalse($email1->equals($email2));
    }

    public function test_can_handle_unicode_domains()
    {
        // Unicode domains are not supported by PHP's filter_var
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email format user@тест.рф');

        Email::fromString('user@тест.рф');
    }

    public function test_can_handle_long_emails()
    {
        $longEmail = 'very.long.username.that.might.exceed.normal.limits@very.long.domain.name.that.might.also.exceed.normal.limits.com';
        $email = Email::fromString($longEmail);

        $this->assertEquals($longEmail, $email->toString());
    }
}
