<?php

declare(strict_types=1);

namespace Feature\Runs;

use App\Runs\Infrastructure\Database\Eloquent\Models\RunModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RunScopesTest extends TestCase
{
    use RefreshDatabase;

    public function test_scopes_for_user_between_recent(): void
    {
        RunModel::factory()->count(2)->create([
            'user_id' => 1,
            'run_at' => Carbon::parse('2025-09-10 10:00:00', 'UTC'),
        ]);

        RunModel::factory()->count(2)->create([
            'user_id' => 2,
            'run_at'  => Carbon::parse('2025-09-12 10:00:00', 'UTC'),
        ]);

        RunModel::factory()->create([
            'user_id' => 1,
            'run_at'  => Carbon::parse('2025-08-01 10:00:00', 'UTC'), // вне диапазона
        ]);

        $from = Carbon::parse('2025-09-01 00:00:00', 'UTC');
        $to   = Carbon::parse('2025-09-30 23:59:59', 'UTC');

        $ids = RunModel::query()
            ->forUser(1)
            ->between($from, $to)
            ->recent()
            ->pluck('user_id')
            ->all();

        $this->assertCount(2, $ids);
        $this->assertEquals([1, 1], $ids);
    }
}
