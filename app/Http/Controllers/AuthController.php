<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Handle the registration form submission
    public function register(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' checks if password matches password_confirmation
        ]);

        // 2. Create the user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Always hash passwords!
        ]);

        // 3. Log the user in automatically
        Auth::login($user);

        // 4. Redirect them to the homepage (or dashboard)
        return redirect('/')->with('success', 'Account created successfully!');
    }

    // Handle the login form submission
    public function login(Request $request)
    {
        // 1. Validate the inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt to log in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security measure

            return redirect()->intended('/')->with('success', 'Logged in successfully!');
        }

        // 3. If login fails, go back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}