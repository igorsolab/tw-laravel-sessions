<?php

    namespace App\Services;

use Illuminate\Support\Facades\Http;

    class CepServices
    {
        public function consultar(string $cep)
        {
            $app_key = "l1bXa8AC3XUX6laaklHop7rcSlNE0yq7";
            $app_secret = "5mVwKSEYF6e03BiEjAubkDYeRCoCkNC71tB6GR1yKY5p2ugF";
            
            $response = Http::withOptions(['verify'=>false])->get("https://webmaniabr.com/api/1/cep/$cep/?app_key=$app_key&app_secret=$app_secret");
            return $response->json();
        }
        public function validar(string $cep)
        {
            $app_key = "l1bXa8AC3XUX6laaklHop7rcSlNE0yq7";
            $app_secret = "5mVwKSEYF6e03BiEjAubkDYeRCoCkNC71tB6GR1yKY5p2ugF";
            
            $response = Http::withOptions(['verify'=>false])->get("https://webmaniabr.com/api/1/cep/$cep/?app_key=$app_key&app_secret=$app_secret");
            $endereco = $response->json();
            return !isset($endereco['error']);
        }
    }