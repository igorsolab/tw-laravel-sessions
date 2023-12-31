<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /***
     * Mostrar formulário para cadastro de usuários
     * 
     */
    public function create()
    {
        return view('auth.register');
    }
    public function store(Request $request)
    {
        $request->validate([
            "name"=>['required','string','max:255'],
            "email"=> ['required', 'string', 'email', 'unique:users','max:255'],
            "password" => ['required','string', 'min:8', 'confirmed']
        ]);
        $dados = $request->only(['name','email','password']);
        $dados['password'] = Hash::make($dados['password']);
        User::create($dados);
        return redirect()->route('home');
    }
}
