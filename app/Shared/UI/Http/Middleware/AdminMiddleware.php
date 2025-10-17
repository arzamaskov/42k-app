<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Недостаточно прав доступа');
        }

        return $next($request);
    }
}
