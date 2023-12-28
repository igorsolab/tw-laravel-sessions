<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    public function buscarRepositorio()
    {
        $response = Http::withOptions(['verify'=>false])->get('https://api.github.com/search/repositories?q=igorlops');
        $repositories= [];
        if($response->successful()){
            $repositories = $response->json()['items'];
        }
        return $repositories;
    }
}