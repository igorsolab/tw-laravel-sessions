<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function logar(Request $request)
    {
        $dados = $request->validate([
            'email'=>['required','email'],
            'password'=> ['required']
        ]);
        if(Auth::attempt($dados, $request->filled('remember'))){
            $request->session()->regenerate();
            return redirect()->intended('home');
        }
        return back()->withErrors([
            'email' => 'O email ou senha não são válidos'
        ]);
    }
    public function logout(Request $request)
    {
        // auth()->logout();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
