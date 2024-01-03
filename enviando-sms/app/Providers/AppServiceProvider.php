<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('App\Services\SMS\SmsServiceInterface', function($app){
            $token = config('services.infobip.token');
            $url = config('services.infobip.url');
            return new \App\Services\SMS\Provider\InfobipProvider($token,$url);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
