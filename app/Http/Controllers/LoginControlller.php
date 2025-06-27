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
        return view('auth.auth-login');
    }

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
}
