<?php

declare(strict_types=1);

namespace App\Users;

use App\Users\Domain\Contracts\UserRepository;
use App\Users\Infrastructure\Database\Eloquent\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
