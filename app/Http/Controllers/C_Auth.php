<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class C_Auth extends Controller
{
    /**
     * Display the login page.
     */
    public function loginPage(): View
    {
        return view('auth.loginPage');
    }

    /**
     * Handle an authentication attempt.
     */
    public function loginProses(Request $request): JsonResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json(['status' => 'NO_USER']);
        }

        if (! Hash::check($request->password, $user->password) && ! password_verify($request->password, $user->password)) {
            return response()->json(['status' => 'WRONG_PASSWORD']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['status' => 'SUCCESS']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

