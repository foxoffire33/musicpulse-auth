<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class Policy
{
    use HandlesAuthorization;

    /**
     * Admin can do everything
     * @param $user
     * @param $ability
     * @return Response
     */
    public function before($user, $ability)
    {
        return ($user->role == \App\Models\User::USER_ROLE_ADMIN ? Response::allow() : Response::deny());
    }
}