<?php

namespace App\Services\SMS;

interface SmsServiceInterface
{
    public function send(string $celNumber, string $msg):int;
}