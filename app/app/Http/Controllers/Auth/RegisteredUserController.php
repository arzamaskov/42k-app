<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Users\Domain\Contracts\UserRepository;
use App\Users\Domain\Entity\User;
use App\Users\Infrastructure\Database\Eloquent\Models\UserModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userEntity = new User(
            $request->name,
            $request->email,
            Hash::make($request->password),
        );

        $user = $this->userRepository->store($userEntity);
        $userModel = UserModel::query()->find($user->ulid);

        event(new Registered($userModel));

        Auth::login($userModel);

        return redirect(route('dashboard', absolute: false));
    }
}
