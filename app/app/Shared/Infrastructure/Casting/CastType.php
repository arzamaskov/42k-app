<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Casting;

enum CastType
{
    case IntNullable;
    case DateTimeImmutableNullable;
}
