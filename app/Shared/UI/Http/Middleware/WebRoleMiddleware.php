<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Middleware;

use App\Users\Domain\ValueObjects\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $userRole = $user->role ?? Role::ROLE_USER;

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Недостаточно прав доступа');
        }

        return $next($request);
    }
}
