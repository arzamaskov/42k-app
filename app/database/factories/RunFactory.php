<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Runs\Infrastructure\Database\Eloquent\Models\RunModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RunFactory extends Factory
{
    protected $model = RunModel::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        $distance = $this->faker->numberBetween(6000, 12000); // 6-12 км
        $pace = $this->faker->numberBetween(300, 390); // 5:00-6:30 мин/км
        $duration = (int) round(($distance / 1000) * $pace);

        $runAt = Carbon::now('UTC')
            ->subDays($this->faker->numberBetween(0, 60))
            ->subHours($this->faker->numberBetween(0, 23))
            ->subMinutes($this->faker->numberBetween(0, 59));

        return [
            'user_id' => 1,
            'run_at' => $runAt,
            'distance' => $distance,
            'duration' => $duration,
            'avg_hr' => $this->faker->optional(0.7)->numberBetween(120, 175),
            'cadence' => $this->faker->optional(0.7)->numberBetween(150, 190),
            'rpe' => $this->faker->optional(0.7)->numberBetween(3, 8),
            'shoe_id' => $this->faker->optional(0.4)->numberBetween(1, 5),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
