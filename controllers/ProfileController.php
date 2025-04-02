<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // Display the user's profile
        $user = Auth::user();
        return view('profile.index', ['user' => $user]);
    }

    public function edit()
    {
        // Show the form to edit the user's profile
        $user = Auth::user();
        return view('profile.edit', ['user' => $user]);
    }

    public function updateEmail(Request $request)
    {
        // Update the user's email
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        // $user->save();

        return redirect()->route('profile')->with('success', 'Email updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        // Update the user's password
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        // $user->save();

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }
}