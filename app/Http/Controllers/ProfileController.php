<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        // Pas de views aan zodat je de juiste item counts kunt tonen in de knoppen op de profiel pagina.
        $favorites = Auth::user()->favorites;
        return view('profile.index', ['favorites' => $favorites]);
    }

    public function edit()
    {
        // Vul het email adres van de ingelogde gebruiker in het formulier in=>
        // Giriş yapmış kullanıcı bilgilerini al
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        // Valideer het formulier, zorg dat het terug ingevuld wordt, en toon de foutmeldingen
        // Emailadres is verplicht en moet uniek zijn (behalve voor het huidge id van de gebruiker).


        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                // E-posta adresini başka kullanıcılarla çakışmaması için kontrol et
                Rule::unique('users')->ignore(Auth::id()),
            ]
        ]);

        // Update de gegevens van de ingelogde gebruiker
        DB::table('users')
            ->where('id', $user->id)
            ->update(['email' => $validated['email']]);

        // BONUS: Stuur een e-mail naar de gebruiker met de melding dat zijn e-mailadres gewijzigd is.
        // Mail::to($user->email)->send(new EmailChangedMail($user));

        return redirect()->route('profile.edit')->with('success', 'E-mailadres is bijgewerkt.');
    }

    public function updatePassword(Request $request)
    {
        // Valideer het formulier, zorg dat het terug ingevuld wordt, en toon de foutmeldingen
        // Wachtwoord is verplicht en moet confirmed zijn.
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Update de gegevens van de ingelogde gebruiker met het nieuwe "hashed" password
        $user =    $user = $request->user();

        // Şifreyi güvenli bir şekilde hash'le ve kaydet
        $user->password = Hash::make($validated['password']);
        $user->save();

        // BONUS: Stuur een e-mail naar de gebruiker met de melding dat zijn wachtwoord gewijzigd is.
        // Mail::to($user->email)->send(new PasswordChangedMail($user));

        return redirect()->route('profile.edit')->with('success', 'Wachtwoord is bijgewerkt.');
        return back();
    }
}
