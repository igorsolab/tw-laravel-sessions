<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SMS\SmsServiceInterface;

class SmsVerificationController extends Controller
{
    public function send(string $celNumber, SmsServiceInterface $smsService)
    {
        dd("Cheguei aqui");
        $code = \mt_rand(1000,9000);
        session(['code'=>$code]);

        $response = $smsService->send($celNumber,"Seu código de verificação é: $code");

        if($response == 200){
            return 'enviado';
        }
        return response('nao-enviado',$response);
    }

}
