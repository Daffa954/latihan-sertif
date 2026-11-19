<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    private function createUserSession(Request $request, User $user): void
    {
        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_username', $user->username);
        $request->session()->put('user_name', $user->name);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_grade', $user->grade);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (
            !$user || !$user->authenticate(
                $request->input('password'),
                $request->input('email')
            )
        ) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $this->createUserSession($request, $user);

        return redirect('/dashboard')
            ->with('success', 'Login successful!');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'username' => 'required|string|max:255|unique:users',
            'grade' => 'nullable|string|max:255',
        ]);

        $user = User::AddRegisterData($request->only(['name', 'email', 'password', 'username', 'role', 'grade']));

        $this->createUserSession($request, $user);

        return response()->json(['message' => 'Registration successful']);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout successful!');
    }
}
