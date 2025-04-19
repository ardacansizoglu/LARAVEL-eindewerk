<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Display login form
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8'
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('profile'));
        }


        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'E-mail of wachtwoord is niet juist.']);
    }

    /**
     * Display registration form
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with hashed password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash the password
        ]);

        // Login the newly registered user
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to dashboard instead of login page (fixed logic issue)
        return redirect()->route('store.index')->with('success', 'Account succesvol aangemaakt!');
    }

    /**
     * Handle logout request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page
        return redirect()->route('login')->with('success', 'U bent uitgelogd.');
    }
}
