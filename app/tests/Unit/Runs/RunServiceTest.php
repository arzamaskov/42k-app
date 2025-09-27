<?php

declare(strict_types=1);

namespace Tests\Unit\Runs;

use App\Runs\Application\Services\RunService;
use App\Runs\Domain\Contracts\RunRepository;
use App\Runs\Domain\Entity\Run;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RunServiceTest extends TestCase
{
    private RunService $runService;
    private RunRepository&MockObject $runRepository;

    protected function setUp(): void
    {
        $this->runRepository = $this->createMock(RunRepository::class);
        $this->runService = new RunService($this->runRepository);
    }

    public function test_get_user_runs(): void
    {
        $userId = 'user123';
        $expectedRuns = [
            new Run(
                userId: $userId,
                runAt: new DateTimeImmutable('2025-01-15 10:00:00'),
                distance: 5000,
                duration: 1800,
                id: 'run123'
            )
        ];

        $this->runRepository
            ->expects($this->once())
            ->method('listByUser')
            ->with($userId)
            ->willReturn($expectedRuns);

        $result = $this->runService->getUserRuns($userId);

        $this->assertSame($expectedRuns, $result);
    }

    public function test_get_run_by_id(): void
    {
        $runId = 'run123';
        $expectedRun = new Run(
            userId: 'user123',
            runAt: new DateTimeImmutable('2025-01-15 10:00:00'),
            distance: 5000,
            duration: 1800,
            id: $runId
        );

        $this->runRepository
            ->expects($this->once())
            ->method('find')
            ->with($runId)
            ->willReturn($expectedRun);

        $result = $this->runService->getRun($runId);

        $this->assertSame($expectedRun, $result);
    }

    public function test_create_run(): void
    {
        $userId = 'user123';
        $data = [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800',
            'avg_hr' => '150',
            'cadence' => '180',
            'rpe' => '7',
            'notes' => 'Тестовая пробежка'
        ];

        $expectedRun = new Run(
            userId: $userId,
            runAt: new DateTimeImmutable('2025-01-15 10:00:00'),
            distance: 5000,
            duration: 1800,
            avgHr: 150,
            cadence: 180,
            rpe: 7,
            notes: 'Тестовая пробежка',
            id: 'run123'
        );

        $this->runRepository
            ->expects($this->once())
            ->method('store')
            ->willReturn($expectedRun);

        $result = $this->runService->createRun($userId, $data);

        $this->assertSame($expectedRun, $result);
    }

    public function test_update_run(): void
    {
        $runId = 'run123';
        $data = [
            'run_at' => '2025-01-15 11:00:00',
            'distance' => '10000',
            'duration' => '3600',
            'avg_hr' => '160',
            'cadence' => '185',
            'rpe' => '8',
            'notes' => 'Обновленная пробежка'
        ];

        $existingRun = new Run(
            userId: 'user123',
            runAt: new DateTimeImmutable('2025-01-15 10:00:00'),
            distance: 5000,
            duration: 1800,
            id: $runId,
            createdAt: new DateTimeImmutable('2025-01-15 09:00:00'),
            updatedAt: new DateTimeImmutable('2025-01-15 09:00:00')
        );
        $updatedRun = new Run(
            userId: 'user123',
            runAt: new DateTimeImmutable('2025-01-15 11:00:00'),
            distance: 10000,
            duration: 3600,
            avgHr: 160,
            cadence: 185,
            rpe: 8,
            notes: 'Обновленная пробежка',
            id: $runId,
            createdAt: new DateTimeImmutable('2025-01-15 09:00:00'),
            updatedAt: new DateTimeImmutable('2025-01-15 09:00:00')
        );

        // Исправляем: ожидаем только один вызов find (через getRun)
        $this->runRepository
            ->expects($this->once())
            ->method('find')
            ->with($runId)
            ->willReturn($existingRun);

        $this->runRepository
            ->expects($this->once())
            ->method('store')
            ->willReturn($updatedRun);

        $result = $this->runService->updateRun($runId, $data);

        $this->assertSame($updatedRun, $result);
    }

    public function test_delete_run(): void
    {
        $runId = 'run123';

        $this->runRepository
            ->expects($this->once())
            ->method('delete')
            ->with($runId);

        $this->runService->deleteRun($runId);
    }

    public function test_update_nonexistent_run_throws_exception(): void
    {
        $runId = 'nonexistent';
        $data = ['run_at' => '2025-01-15 10:00:00', 'distance' => '5000', 'duration' => '1800'];

        $this->runRepository
            ->expects($this->once())
            ->method('find')
            ->with($runId)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Пробежка с ID {$runId} не найдена");

        $this->runService->updateRun($runId, $data);
    }
}
