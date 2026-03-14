<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required'], // Bisa email atau no hp
            'password' => ['required'],
        ]);

        $login = $request->login;
        $password = $request->password;

        // Cek apakah login menggunakan email atau no hp
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Coba login menggunakan Auth::attempt
        if (Auth::attempt([$fieldType => $login, 'password' => $password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user?->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil.');
            }

            return redirect()->intended('/shop')->with('success', 'Login berhasil.');
        }

        return back()->withErrors([
            'login' => 'Email/No HP atau PIN salah.',
        ])->onlyInput('login');
    }

    // Register
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'address' => ['required', 'string'],
            'password' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/shop');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
