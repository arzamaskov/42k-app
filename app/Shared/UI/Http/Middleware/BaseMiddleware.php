<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseMiddleware
{
    abstract public function handle(Request $request, Closure $next): Response;

    protected function getUser(Request $request)
    {
        return $request->user();
    }

    protected function isAuthenticated(Request $request): bool
    {
        return $request->user() !== null;
    }
}
