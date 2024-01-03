<?php

namespace App\Services\SMS\Provider;

use App\Services\SMS\SmsServiceInterface;
use Illuminate\Support\Facades\Http;

class InfobipProvider implements SmsServiceInterface
{
    private $token;
    private $url;
    public function __construct(string $token, string $url)
    {
        $this->token = $token;
        $this->url = $url;
        dd($this->token);
    }

    public function send(string $celNumber, string $msg):int
    {
        dd($this->token, $this->url);
        $response = Http::withHeaders([
            'Authorization' => "App {$this->token}"
        ])
        ->withOptions(['verify'=>false])
        ->post("{$this->url}/text/advanced",[
            'messages' => [
                'from'=>'treinaweb',
                'destinations' => [
                    'to' => '55'.$celNumber
                ],
                'text' => $msg
            ]
        ]);
        return $response->status();
    }
}