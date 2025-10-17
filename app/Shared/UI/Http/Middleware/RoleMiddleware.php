<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Middleware;

use App\Shared\UI\Http\Responses\BaseResponse;
use App\Users\Domain\ValueObjects\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $userRole = $user->role ?? Role::ROLE_USER;

        if (! in_array($userRole, $roles, true)) {
            return BaseResponse::error(
                'Недостаточно прав доступа',
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
