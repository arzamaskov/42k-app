<?php

declare(strict_types=1);

namespace App\Runs\Application\Services;

use App\Runs\Domain\Contracts\RunRepository;
use App\Runs\Domain\Entity\Run;

readonly class RunService
{
    public function __construct(
        private RunRepository $runRepository,
    ) {
    }

    /**
     * Получить пробежки пользователя
     *
     * @return Run[]
     */
    public function getUserRuns(string $userId): array
    {
        return $this->runRepository->listByUser($userId);
    }

    /**
     * Получить пробежку по ID
     */
    public function getRun(string $runId): ?Run
    {
        return $this->runRepository->find($runId);
    }

    /**
     * Создать новую пробежку
     * @throws \Exception
     */
    public function createRun(string $userId, array $data): Run
    {
        $run = new Run(
            userId: $userId,
            runAt: new \DateTimeImmutable($data['run_at']),
            distance: (int) $data['distance'],
            duration: (int) $data['duration'],
            avgHr: isset($data['avg_hr']) ? (int) $data['avg_hr'] : null,
            cadence: isset($data['cadence']) ? (int) $data['cadence'] : null,
            rpe: isset($data['rpe']) ? (int) $data['rpe'] : null,
            shoeId: isset($data['shoe_id']) ? (int) $data['shoe_id'] : null,
            notes: $data['notes'] ?? null,
        );

        return $this->runRepository->store($run);
    }

    /**
     * Обновить пробежку
     * @throws \Exception
     */
    public function updateRun(string $runId, array $data): Run
    {
        $existingRun = $this->getRun($runId);
        if (!$existingRun) {
            throw new \RuntimeException("Пробежка с ID {$runId} не найдена");
        }

        $updatedRun = new Run(
            userId: $existingRun->userId,
            runAt: new \DateTimeImmutable($data['run_at']),
            distance: (int) $data['distance'],
            duration: (int) $data['duration'],
            avgHr: isset($data['avg_hr']) ? (int) $data['avg_hr'] : null,
            cadence: isset($data['cadence']) ? (int) $data['cadence'] : null,
            rpe: isset($data['rpe']) ? (int) $data['rpe'] : null,
            shoeId: isset($data['shoe_id']) ? (int) $data['shoe_id'] : null,
            notes: $data['notes'] ?? null,
            id: $runId,
            createdAt: $existingRun->createdAt,
            updatedAt: $existingRun->updatedAt,
        );

        return $this->runRepository->store($updatedRun);
    }

    /**
     * Удалить пробежку
     */
    public function deleteRun(string $runId): void
    {
        $this->runRepository->delete($runId);
    }
}
