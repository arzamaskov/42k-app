<?php

declare(strict_types=1);

namespace App\Users\UI\Http;

use App\Shared\UI\Http\Controller;
use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function register(): JsonResponse
    {
        $validated = $this->request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:App\Users\Infrastructure\Database\Eloquent\Models\User',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(): JsonResponse
    {
        $validated = $this->request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);
        if (! Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function user(): JsonResponse
    {
        $user = request()->user();

        return response()->json($user, 200);
    }
}
