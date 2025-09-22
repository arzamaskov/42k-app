<?php

declare(strict_types=1);

namespace App\Runs\Domain\Entity;

final class Run
{
    public function __construct(
        public readonly int $userId,
        public readonly \DateTimeImmutable $runAt,
        public readonly int $distance,
        public readonly int $duration,
        public readonly ?int $avgHr = null,
        public readonly ?int $cadence  = null,
        public readonly ?int $rpe = null,
        public readonly ?int $shoeId = null,
        public readonly ?string $notes = null,
        public readonly ?int $id = null,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null
    ) {

    }

    /** Возвращает темп в сек/км (или null, если дистанция нулевая). */
    public function getPace(): ?int
    {
        if ($this->distance <= 0) {
            return null;
        }

        return (int) round($this->duration * 1000 / $this->distance);
    }

    /** Форматированный темп вида mm:ss на км (или null). */
    public function formatPace(): ?string
    {
        $pace = $this->getPace();
        if ($pace === null) {
            return null;
        }
        $min = intdiv($pace, 60);
        $sec = $pace % 60;

        return sprintf('%d:%02d', $min, $sec);
    }
}
