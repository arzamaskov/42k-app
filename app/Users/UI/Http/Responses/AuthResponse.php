<?php

declare(strict_types=1);

namespace App\Users\UI\Http\Responses;

use App\Shared\UI\Http\Responses\BaseResponse;
use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthResponse extends BaseResponse
{
    public static function registered(User $user, string $token): JsonResponse
    {
        return self::success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    public static function loggedIn(User $user, string $token): JsonResponse
    {
        return self::success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
        ]);
    }

    public static function invalidCredentials(): JsonResponse
    {
        return self::error('Неверный email или пароль', Response::HTTP_UNAUTHORIZED);
    }

    public static function loggedOut(): JsonResponse
    {
        return self::message('Вы успешно вышли из системы');
    }

    public static function userProfile(User $user): JsonResponse
    {
        return self::success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
        ]);
    }
}
