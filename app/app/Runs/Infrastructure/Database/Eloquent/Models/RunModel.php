<?php

declare(strict_types=1);

namespace App\Runs\Infrastructure\Database\Eloquent\Models;

use Carbon\CarbonInterface;
use Database\Factories\RunFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class RunModel extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'runs';

    protected $fillable = [
        'user_id',
        'run_at',
        'distance',
        'duration',
        'avg_hr',
        'cadence',
        'rpe',
        'shoe_id',
        'notes',
        'id'
    ];

    protected $casts = [
        'run_at' => 'immutable_datetime',
        'distance' => 'integer',
        'duration' => 'integer',
        'avg_hr' => 'integer',
        'cadence' => 'integer',
        'rpe' => 'integer',
        'shoe_id' => 'integer',
    ];

    protected static function newFactory(): RunFactory
    {
        return RunFactory::new();
    }

    /** Показывать пробежки конкретного пользователя */
    public function scopeForUser(Builder $q, string $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    /** Ограничить по диапазону времени (включительно) */
    public function scopeBetween(Builder $q, CarbonInterface $from, CarbonInterface $to): Builder
    {
        return $q->whereBetween('run_at', [$from, $to]);
    }

    /** Сортировка по дате забега — новые сначала */
    public function scopeRecent(Builder $q): Builder
    {
        return $q->orderByDesc('run_at');
    }
}
