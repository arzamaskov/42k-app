<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Casting;

final class Caster
{
    /**
     * @throws \Exception
     */
    public static function cast(mixed $value, CastType $castType): \DateTimeImmutable|int|null
    {
        return match ($castType) {
            CastType::IntNullable => $value === null ? null : (int) $value,
            CastType::DateTimeImmutableNullable => self::toDateTimeImmutableOrNull($value),
        };
    }

    /**
     * @throws \Exception
     */
    public static function toDateTimeImmutableOrNull(mixed $value): ?\DateTimeImmutable
    {
        if ($value instanceof \DateTimeImmutable) {
            return $value;
        }
        if ($value instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($value);
        }
        if (is_string($value) && $value !== '') {
            return new \DateTimeImmutable($value);
        }

        return null;
    }
}
