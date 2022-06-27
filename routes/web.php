<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//todo router bestand ga ik nog aanpassen
$router->group([], function () use ($router) {

    /**
     * @group General
     * Get API version
     */
    $router->get('/', function () use ($router) {
        return ["application" => config('app.name'), "version" => "0.1"];
    });

    /**
     * @Ratelimiting 1 times a minute
     */
    $router->group(['prefix' => 'devices', 'middleware' => ['throttle:1000,1']], function () use ($router) {
        //register new device and update
        //todo Security only our app should post
        $router->post('/', 'DevicesController@create');
        $router->put('/{id}', 'DevicesController@update');
    });

    /**
     * @group Authentication
     *
     * @Ratelimiting 5 times a minute
     */
    $router->group(['middleware' => ['throttle:5,1']], function () use ($router) {
        $router->post('/', 'AuthenticationController@basic');
        $router->group(['middleware' => ['auth']], function () use ($router) {
            $router->get('/jwt', 'AuthenticationController@jwt');
        });
    });
    $router->group(['prefix' => 'users/admin', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'Admin\UsersController@index');
        $router->get('/trashed', 'Admin\UsersController@trashed');
        $router->post('/{id}/restore', 'Admin\UsersController@restore');
        $router->delete('/{id}/force-delete', 'Admin\UsersController@forceDelete');
    });

    $router->group(['prefix' => 'users', 'middleware' => ['auth']], function () use ($router) {
        $router->delete('/{uuid}', 'UsersController@delete');
        $router->get('/', 'UsersController@index');
        $router->get('/{uuid}', 'UsersController@show');

        $router->post('/', 'UsersController@create');
        $router->put('/{uuid}', 'UsersController@update');
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {

        //admin routers
        $router->group(['prefix' => 'devices/admin'], function () use ($router) {
            $router->get('/', 'Admin\DevicesController@index');
            $router->get('/trashed', 'Admin\DevicesController@trashed');
            $router->post('/{id}/restore', 'Admin\DevicesController@restore');
            $router->delete('/{id}/force-delete', 'Admin\DevicesController@forceDelete');

            $router->group(['prefix' => 'notification'], function () use ($router) {
                $router->group(['middleware' => ['throttle:1,20']], function () use ($router) {
                    $router->post('/token', 'Admin\NotificationsController@removeTokenFromAll',[]);
                });
                $router->post('/alert', 'Admin\NotificationsController@sendAlertToAllDevices');
            });
        });

        $router->group(['prefix' => 'devices'], function () use ($router) {
            $router->get('/', 'DevicesController@index');
            $router->get('/{id}', 'DevicesController@show');
            $router->delete('/{id}', 'DevicesController@destroy');

            /**
             * Notifications routes voor one device
             * @Ratelimiting 1 request in 5 minutes
             */
            $router->group(['prefix' => '{uuid}/notification', 'middleware' => ['throttle:1,20']], function () use ($router) {
                $router->post('/alert', 'NotificationsController@sendAlertToDevice');
                $router->post('/playlists', 'NotificationsController@updatePlaylistsOnDevice');
                $router->post('/token', 'NotificationsController@updateTokenOnDevice');
                $router->post('/token', 'NotificationsController@removeTokenFromDevice');
            });
        });
    });


});