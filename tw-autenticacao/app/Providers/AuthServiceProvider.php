<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Post'=>'App\Policies\PostPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate::define('update-post',function($user){
        //     if($user->type == 'admin'){
        //         return Response::allow();
        //     }
        //     return Response::deny('Você precisa de autorização');
        // });
        // Gate::define('delete-post',function($user,$post){
        //     if($post->owner == $user->id){
        //         return Response::allow();
        //     }
        //     return Response::deny('Somente o dono do post pode excluí-lo');
        // });
    }
}
