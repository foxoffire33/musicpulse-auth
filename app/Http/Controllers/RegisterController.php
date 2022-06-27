<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use GuzzleHttp\Psr7\Request;

class RegisterController
{
    /**
     * Create new device for user
     *
     * @bodyParam id uuid required Het unique id die door de fabrikant gegenereerd is Example: 0FDF668C-074A-4A62-BCEC-3CBEDE25C524
     * @bodyParam name string required De naam van de gebruiker Example: Hank buis
     * @bodyParam email string required De Email address van de gebruiker
     * @bodyParam deviceToken DeviceID
     * @response status=201 scenario="success" {"message": "Created Successfully"}
     * @response status=404 scenario="not found" {"message": "Niet gevonden"}
     * @response status=405 scenario="unauthirzed" {"message": "Unauthirzed"}
     * @response status=422 scenario="error" {"message": "The given data was invalid"}
     * @authenticated
     */
    //todo testen
    public function create(\Illuminate\Http\Request $request)
    {
        $model = new User($request->all());
        if($model->save())
            return ['token' => AuthenticationController::generateNewJWTToken($model)];

        return response('Not saved', 422);
    }
}