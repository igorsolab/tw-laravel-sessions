<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{

    /**
     * Mostra o formulário de login
     * 
     * @return  \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Realiza login com os dados enviados
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
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
    /**
     * Realiza logout do usuário
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector 
     * 
     */
    public function logout(Request $request)
    {
        // auth()->logout();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
