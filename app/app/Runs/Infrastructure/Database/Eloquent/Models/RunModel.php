<?php

declare(strict_types=1);

namespace App\Runs\Infrastructure\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

final class RunModel extends Model
{
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
}
