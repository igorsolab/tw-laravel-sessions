<?php

namespace App\Http\Controllers;

use App\Services\GithubService;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function buscar(GithubService $github)
    {
        $repositories = $github->buscarRepositorio();
        return view('github.buscar', ['repositories'=>$repositories]);
    }
}
