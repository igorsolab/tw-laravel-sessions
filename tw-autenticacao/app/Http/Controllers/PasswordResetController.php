<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    /**
     * Mostra o formulário para requisitar mensagem de recuperação de senha
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function request()
    {
        return view('auth.passwords.email');
    }


    /**
     * Envia a mensagem de email para o endereço do usuário
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function email(Request $request)
    {
        $request->validate([
            'email' => ['required','email']
        ]);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status'=> __($status)])
            : back()->withErrors(['email'=>__($status)]);
    }

    /**
     * Mostra o form de alteração de senha
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function reset()
    {
        return view('auth.passwords.reset');
    }

    /**
     * Realiza a alteração da senha no banco de dados
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $request->validate([
            'token'=>['required'],
            'email' => ['required','email'],
            'password' => ['required','string','min:8', 'confirmed']
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function($user,$password){
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email'=>[__($status)]]);
        
    }
}
