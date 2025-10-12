<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Providers;

use App\Users\Domain\Repositories\UserRepositoryInterface;
use App\Users\Infrastructure\Database\Eloquent\Models\PersonalAccessToken;
use App\Users\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
