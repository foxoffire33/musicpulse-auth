<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.


        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->get('token') ?? $request->bearerToken();
            if ($token) {
                try {
                    $jwtDecode = JWT::decode($request->bearerToken(), config('jwt.key'), [config('jwt.alg')]);
                    return User::findOrFail($jwtDecode->id);
                } catch (ExpiredException $e) {
                    //Provided token is expired.
                } catch (Exception $e) {
                    //An error while decoding token.
                }
            }
        });
    }
}
