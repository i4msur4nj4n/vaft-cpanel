<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            AuditLog::record('UPDATE', 'User logged in from IP: ' . $request->ip());
            return redirect()->intended('/dashboard');
        }

        // Log failed login attempt
        $user = User::where('email', $credentials['email'])->first();
        AuditLog::record('UPDATE', 'Failed login attempt for: ' . $credentials['email'] . ' from IP: ' . $request->ip(), $user?->id);

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        AuditLog::record('CREATE', 'New user registered: ' . $user->name . ' (' . $user->email . ')', $user->id);
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userName = Auth::user()?->name ?? 'Unknown';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        AuditLog::record('UPDATE', 'User logged out: ' . $userName, $userId);
        return redirect('/');
    }
}
