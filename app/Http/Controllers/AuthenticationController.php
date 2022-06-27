<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceUserrelations;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * APIs for authenticateion
 *
 */
class AuthenticationController extends Controller
{
    /**
     * Basic login
     *
     * This is used for first time authentication. when your user has the role <b>Deivce</b> the devoceToken parameter is required
     *
     * @bodyParam email string required E-mail of the user. Example: development@dxmusic.nl
     * @bodyParam password string required  Password of the user.
     * @bodyParam deviceToken string Required when user role is deivce.
     *
     * @response 200 {
     *  "token": "String"
     * }
     *  @response 401 "error": {
     *  "code": 401,
     *  "message": "Invalid credentials."
     * }
     * @response 429  error": {
     *  "code": 429,
     *  "message": "Too Many Attempts."
     * }
     **/
    public function basic(Request $request)
    {
        try {
            $user = User::where('email', $request->post('email'))->firstOrFail();
            //validate password
            if (Hash::check($request->get('password'), $user->password)) {
                //check if user has role device register device to user
                if ($user->role == User::USER_ROLE_DEVICE) {
                    if ($device = Device::where('device_token',$request->post('deviceToken'))->firstOrFail()) {
                        $user->devices()->attach($device->id);
                        return ['token' => AuthenticationController::generateNewJWTToken(User::where('email', $request->post('email'))->first())];
                    }
                    abort(401, "Invalid credentials");
                }
                return ['token' => AuthenticationController::generateNewJWTToken($user)];
            }
            abort(401, "Invalid credentials");
        } catch (ModelNotFoundException $e){
            abort(401, "Invalid credentials");
        }
    }

    /**
     * Renew token
     *
     * With this endpoint you can request an new token, the current one should be valid.
     * @authenticated
     *
     * @response {
     *  "token": "String"
     * }
     *  @response 401 "error": {
     *  "code": 401,
     *  "message": "Unauthorized."
     * }
     * @response 429  error": {
     *  "code": 429,
     *  "message": "Too Many Attempts."
     * }
     **/
    public function jwt()
    {
        return ['token' => AuthenticationController::generateNewJWTToken(Auth::user())];
    }

    static function generateNewJWTToken($user): string
    {
        //load defualt payload
        $payload = config('jwt.payload');

        //add payload options
        $payload['aud'] = 'api.dxmusic.nl/v1/research';
        $payload['iat'] = time();
        $payload['exp'] = time() + 604800;// een week
        $payload['id'] = $user->id;
        $payload['role'] = $user->role;
        $payload['email'] = $user->email;

        return JWT::encode(
            $payload,
            config('app.key'),
            config('jwt.alg')
        );
    }

}
