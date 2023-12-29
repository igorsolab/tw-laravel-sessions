<?php

namespace App\Http\Controllers;

use App\Services\CepServices;
use Illuminate\Http\Request;

class CepController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($cep, CepServices $cepservices)
    {
        return $cepservices->consultar($cep);   
    }
}
