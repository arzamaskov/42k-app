<?php

declare(strict_types=1);

namespace App\Users\UI\Http;

use App\Shared\UI\Http\Controller;
use App\Users\Domain\Repositories\UserRepositoryInterface;
use App\Users\UI\Http\Requests\LoginRequest;
use App\Users\UI\Http\Requests\RegisterRequest;
use App\Users\UI\Http\Responses\AuthResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $validated = $registerRequest->validated();

        $user = $this->userRepository->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return AuthResponse::registered($user, $token);
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
