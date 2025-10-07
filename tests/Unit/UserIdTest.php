<?php

namespace Tests\Unit;

use App\Users\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function test_can_generate_user_id(): void
    {
        $userId = UserId::generate();

        self::assertInstanceOf(UserId::class, $userId);
        self::assertIsString($userId->toString());
        self::assertEquals(26, strlen($userId->toString()));
    }

    public function test_can_create_from_string(): void
    {
        $ulid = '01HQZX3Y6KQZX3Y6KQZX3Y6KQZ';
        $userId = UserId::fromString($ulid);

        self::assertEquals($ulid, $userId->toString());
    }

    public function test_equals_compares_values(): void
    {
        $ulid = '01HQZX3Y6KQZX3Y6KQZX3Y6KQZ';
        $userId1 = UserId::fromString($ulid);
        $userId2 = UserId::fromString($ulid);
        $userId3 = UserId::generate();

        self::assertTrue($userId1->equals($userId2));
        self::assertFalse($userId1->equals($userId3));
    }

    public function test_generated_id_has_correct_format()
    {
        $userId = UserId::generate();
        $idString = $userId->toString();

        // ULID должен содержать только допустимые символы и быть длиной 26
        self::assertMatchesRegularExpression('/^[0-9A-HJKMNP-TV-Z]{26}$/', $idString);
    }
}
