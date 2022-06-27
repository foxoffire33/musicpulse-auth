<?php

namespace App\Policies;

use App\Models\Device;
use App\User;
use Illuminate\Auth\Access\Response;

class DevicePolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     *
     */
    public function viewAny()
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     */
    public function view(User $user, Device $device)
    {
        return $device->users()->contains('user_id',$user->id) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function update(User $user, Device $device)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Device $model
     * @return mixed
     */
    public function delete(User $user, Device $model){
        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\user $model
     * @return mixed
     */
    public function restore(User $user, Device $device)
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
