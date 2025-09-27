<?php

declare(strict_types=1);

namespace App\Runs\Domain\Contracts;

use App\Runs\Domain\Entity\Run;

interface RunRepository
{
    public function find(string $runId): ?Run;

    /** return Run[] */
    public function listByUser(string $userId, int $limit = 50, int $page = 1): array;

    public function store(Run $run): Run;

    public function delete(string $runId): void;
}
