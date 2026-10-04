<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.index', ['mode' => 'login']);
    }

    public function showRegister()
    {
        return view('auth.index', ['mode' => 'register']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi salah.'])
            ->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 60 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback(Request $request)
{
     try {
        $google = Socialite::driver('google')->user();
    } catch (\Throwable $e) {
        Log::error('Google login gagal: ' . get_class($e) . ' - ' . $e->getMessage());

        return redirect()->route('login')
            ->withErrors(['email' => 'Login dengan Google gagal. Coba lagi.']);
    }

    $user = User::where('google_id', $google->getId())->first()
        ?? User::where('email', $google->getEmail())->first();

    if ($user) {
        $user->update(['google_id' => $google->getId()]);
    } else {
        $user = User::create([
            'name'      => $google->getName() ?: 'Pemain',
            'email'     => $google->getEmail(),
            'google_id' => $google->getId(),
            'password'  => null,
        ]);
    }

    Auth::login($user, true);
    $request->session()->regenerate();

    return redirect()->intended(route('home'));
}
}