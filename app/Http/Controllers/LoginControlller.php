<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginControlller extends Controller
{
    /**
     * display the page login
     */
    public function index()
    {
        if(Auth::check()) return redirect('/app');
        $title = "Login - Acesse sua conta";
        return view('auth.auth-login', compact('title'));
    }

    /**
     * Login a user in app
     */
    public function login(Request $request):RedirectResponse
    {
        $credentias = $request->validate([
            'email' => "bail|required|email:rfc|exists:users",
            'password' => "required|min:8"
        ]);

        $remember = $request->boolean('remember');

        if(Auth::attempt($credentias, $remember)){
            $request->session()->regenerate();
            return redirect()->intended('app');
        }

        return back()->with([
            'credentials' => 'Verifique seu email e senha e tente novamente',
            'message-type' => 'warning' 
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/entrar');
    }
}
