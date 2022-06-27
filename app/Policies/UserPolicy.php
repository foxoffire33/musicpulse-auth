<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function view(User $user, user $model)
    {
        return ($user->id == $model->id ? Response::allow() : Response::deny());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function update(User $user, user $model)
    {
        return ($user->id == $model->id ? Response::allow() : Response::deny());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function delete(User $user, user $model)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function restore(User $user, user $model)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function forceDelete(User $user, user $model)
    {
        return Response::deny();
    }
}
