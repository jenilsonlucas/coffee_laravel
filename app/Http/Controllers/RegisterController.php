<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{ 
    /**
     * display the page of register 
     */
    public function index()
    {
        if(Auth::check()) return redirect('/app');
        
        return view('auth.auth-register');
    }

    /**
     * register an user
     */
    public function register(Request $request):RedirectResponse
    {        
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email:rfc|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'photo' => '/assets/images/avatar.jpg' 
        ]);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->route('verification.notice', ['email' => $user->email]);
    }
}
