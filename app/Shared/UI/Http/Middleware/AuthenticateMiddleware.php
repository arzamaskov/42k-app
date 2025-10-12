<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Middleware;

use App\Shared\UI\Http\Responses\BaseResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем токен и устанавливаем пользователя
        $user = Auth::guard('sanctum')->user();

        if ($user === null) {
            return BaseResponse::error(
                'Необходима аутентификация',
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Устанавливаем пользователя в контекст запроса
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
