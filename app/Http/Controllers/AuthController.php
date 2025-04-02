<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        // Valideer het formulier
        $credentials = $request->validate('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Schrijf de aanmeld logica om in te loggen.
        if (Auth::attempt($credentials)) {
            // Oturum güvenliği için session yenile
            $request->session()->regenerate();
            // Giriş yaptıysanız ziyaretçiyi amaçlanan "profil" rotasına yönlendirin (aşağıya bakın)
            return redirect()->intended(route('profile'));
        }

        // Bilgiler yanlışsa forma geri dön
        // e-posta alanına verinin yanlış olduğunu bildiren bir mesaj!!
        return redirect()->back()->withErrors(['email' => 'De ingevoerde gegevens zijn niet correct'])->withInput();
    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        // Valideer het formulier.
        $data = $request->only('name', 'email', 'password', 'password_confirmation');

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Bewaar een nieuwe gebruiker in de databank met een beveiligd wachtwoord.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($validator['password']), // Şifreyi şifrele
        ]);

        // Kullanıcıyı hemen oturum açtırmak isterseniz
        Auth::login($user);
        $request->session()->regenerate();

        // BONUS: Verstuur een email naar de gebruiker waarin staat dat er een nieuwe account geregistreerd is voor de gebruiker.
        // Mail::to($user->email)->send(new WelcomeMail($user));

        return redirect()->route('login')->with('success', 'Je bent succesvol geregistreerd! Je kunt nu inloggen.');
        // Başka bir sayfaya yönlendirmek isterseniz, aşağıdaki satırı kullanabilirsiniz
    }

    public function logout(Request $request)
    {
        // Oturum güvenliği için session yenile
        $request->session()->regenerateToken();
        $request->session()->invalidate();

        // Gebruiker moet uitloggen
        return redirect('/')->with('success', 'Je bent succesvol uitgelogd!');
        return redirect()->route('login');
        return back();
    }
}