<?php

declare(strict_types=1);

namespace App\Runs;

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
    }

    public function boot(): void
    {
        //
    }
}
