<?php

declare(strict_types=1);

namespace App\Runs\Infrastructure\Database\Eloquent\Repositories;

use App\Runs\Domain\Contracts\RunRepository;
use App\Runs\Domain\Entity\Run;
use App\Runs\Infrastructure\Database\Eloquent\Models\RunModel;
use App\Shared\Infrastructure\Casting\Caster;
use App\Shared\Infrastructure\Casting\CastType;
use http\Exception\RuntimeException;

class EloquentRunRepository implements RunRepository
{
    /**
     * @throws \Exception
     */
    public function find(string $runId): ?Run
    {
        $model = RunModel::query()->find($runId);
        if (null === $model) {
            return null;
        }
        if (!$model instanceof RunModel) {
            throw new RuntimeException('Unexpected model type in EloquentRunRepository::find');
        }

        return $this->mapToEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function listByUser(string $userId, int $limit = 50, int $page = 1): array
    {
        $items = RunModel::query()
            ->where('user_id', $userId)
            ->orderByDesc('run_at')
            ->forPage($page, $limit)
            ->get();

        return $items
            ->map(fn (RunModel $model) => $this->mapToEntity($model))
            ->all();
    }

    /**
     * @throws \Exception
     */
    public function store(Run $run): Run
    {
        $model = $run->id ? RunModel::query()->find($run->id) : new RunModel();

        if ($run->id && !$model) {
            throw new RuntimeException("Run with id={$run->id} not found for update");
        }
        if (!$model instanceof RunModel) {
            throw new RuntimeException('Unexpected model type in EloquentRunRepository::store');
        }

        $model->setAttribute('user_id', $run->userId);
        $model->setAttribute('run_at', $run->runAt);     // DateTimeImmutable, Laravel кастанёт
        $model->setAttribute('distance', $run->distance);  // м
        $model->setAttribute('duration', $run->duration);  // с
        $model->setAttribute('avg_hr', $run->avgHr);
        $model->setAttribute('cadence', $run->cadence);
        $model->setAttribute('rpe', $run->rpe);
        $model->setAttribute('shoe_id', $run->shoeId);
        $model->setAttribute('notes', $run->notes);

        $model->save();

        return $this->mapToEntity($model);
    }

    public function delete(string $runId): void
    {
        RunModel::query()->whereKey($runId)->delete();
    }

    /**
     * @throws \Exception
     */
    private function mapToEntity(RunModel $model): Run
    {
        $runAt = Caster::cast($model->getAttribute('run_at'), CastType::DateTimeImmutableNullable);
        if (!$runAt instanceof \DateTimeImmutable) {
            throw new \RuntimeException('runs.run_at is null or invalid');
        }

        return new Run(
            userId:    (string) $model->getAttribute('user_id'),
            runAt:     $runAt,
            distance:  (int) $model->getAttribute('distance'),
            duration:  (int) $model->getAttribute('duration'),
            avgHr:     Caster::cast($model->getAttribute('avg_hr'), CastType::IntNullable),
            cadence:   Caster::cast($model->getAttribute('cadence'), CastType::IntNullable),
            rpe:       Caster::cast($model->getAttribute('rpe'), CastType::IntNullable),
            shoeId:    Caster::cast($model->getAttribute('shoe_id'), CastType::IntNullable),
            notes:     $model->getAttribute('notes'),
            id:        (string) $model->getKey(),
            createdAt: Caster::cast($model->getAttribute('created_at'), CastType::DateTimeImmutableNullable),
            updatedAt: Caster::cast($model->getAttribute('updated_at'), CastType::DateTimeImmutableNullable),
        );
    }
}
