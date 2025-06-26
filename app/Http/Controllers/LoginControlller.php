<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginControlller extends Controller
{
    /**
     * display the page login
     */
    public function index()
    {
        return view('auth.auth-login');
    }
}
