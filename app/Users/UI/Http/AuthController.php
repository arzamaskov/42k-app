<?php

declare(strict_types=1);

namespace App\Users\UI\Http;

use App\Shared\UI\Http\Controller;
use App\Users\Application\DTO\CreateUserDTO;
use App\Users\Application\Services\UserService;
use App\Users\Infrastructure\Database\Eloquent\Models\User;
use App\Users\UI\Http\Requests\LoginRequest;
use App\Users\UI\Http\Requests\RegisterRequest;
use App\Users\UI\Http\Responses\AuthResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $validated = $registerRequest->validated();

        $createdUserDTO = CreateUserDTO::fromArray($validated);
        $userDTO = $this->userService->createUser($createdUserDTO);

        $user = User::find($userDTO->getId());
        $token = $user->createToken('auth_token')->plainTextToken;

        return AuthResponse::registered($userDTO, $token);
    }

    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $validated = $loginRequest->validated();
        if (! Auth::attempt($validated)) {
            return AuthResponse::invalidCredentials();
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return AuthResponse::loggedIn($user, $token);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return AuthResponse::loggedOut();
    }

    public function user(): JsonResponse
    {
        $user = request()->user();

        return AuthResponse::userProfile($user);
    }
}
