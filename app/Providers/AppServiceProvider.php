<?php

namespace App\Providers;

use App\Models\Device;
use App\Models\User;
use App\Policies\DevicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       Gate::define('admin', fn($user) => $user->role == User::USER_ROLE_ADMIN);
       Gate::define('manage-notification', fn($user) => $user->role == User::USER_ROLE_ADMIN);
       Gate::define('manage-users', fn($user, $model) => $user->id == $model->id || Gate::check('admin'));
       Gate::define('manage-devices', fn($user, $model) => $user->devices()->pluck('id')->contains($model->id) || Gate::check('admin'));
    }
}
