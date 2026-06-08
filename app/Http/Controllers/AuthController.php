<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials  = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isLeader()) {
                return redirect()->route('cabinet');
            }
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'],
            'role'     => 'visitor',
        ]);

        Auth::login($user);

        return $user->isLeader() ? redirect()->route('cabinet') : redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
