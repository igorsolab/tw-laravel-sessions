<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update($user)
    {
        if($user->type == 'admin'){
            return Response::allow();
        }
        return Response::deny('Você precisa de autorização');
    }

    public function delete($user, $post)
    {
        if($post->owner == $user->id){
            return Response::allow();
        }
        return Response::deny('Somente o dono do post pode excluí-lo');
    }
}
