<?php

declare(strict_types=1);

namespace App\Runs;

use App\Runs\Application\Services\RunService;
use App\Runs\Domain\Contracts\RunRepository;
use App\Runs\Infrastructure\Database\Eloquent\Repositories\EloquentRunRepository;
use Illuminate\Support\ServiceProvider;

class RunsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RunRepository::class,
            EloquentRunRepository::class
        );
        $this->app->bind(
            RunService::class,
            function ($app) {
                return new RunService($app->make(RunRepository::class));
            }
        );
    }

    public function boot(): void
    {
        //
    }
}
