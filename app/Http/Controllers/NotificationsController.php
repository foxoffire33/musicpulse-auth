<?php

namespace App\Http\Controllers;

use App\Jobs\APNSData;
use App\Jobs\APSAlert;
use App\Jobs\APNSToken;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use \Illuminate\Http\Request;

/**
 * @group Push Notifications
 * APIs for sending push notifications,
 */
class NotificationsController extends Controller
{

    const ACTION_LOGOUT_DEVICE      = 'logoutDevice';
    const ACTION_REFRESH_TOKEN      = 'refreshToken';
    const ACTION_REFRESH_PLAYLISTS  = 'refreshPlaylists';

    public function __construct()
    {
        Gate::authorize('manage-notification');
    }

    /**
     * Send alert to device
     *
     * @authenticated
     * @queryParam uuid uuid required The uuid of the Device.
     * @response status=200 scenario="success" {"message": "alert send."}
     * @response status=404 scenario="device not found" {"message": "Device not found"}
     * @response status=422 scenario="bad request" {"message": "Bad request"}
     * @response status=405 scenario="Unauthorized request" {"message": "Unauthorized request"}
     * @apiResourceModel App\Models\Device
     */
    public function sendAlertToDevice(Request $request, string $uuid)
    {
        $device = Device::findOrFail($uuid);
        dispatch(new APSAlert($device->device_token,$this->alertValidator($request)));
        return response(['message' => "Alert send to device"]);
    }


    /**
     * Logout device
     *
     * @authenticated
     * @bodyParam uuid uuid required The uuid of the Device.
     * @response status=200 scenario="success" {"message": "alert send."}
     * @response status=404 scenario="device not found" {"message": "Device not found"}
     * @response status=422 scenario="bad request" {"message": "Bad request"}
     * @response status=405 scenario="Unauthorized request" {"message": "Unauthorized request"}
     * @apiResourceModel App\Models\Device
     */
    public function removeTokenFromDevice(string $uuid)
    {
        $device = Device::findOrFail($uuid);
        dispatch(new APNSToken($device->device_token,self::ACTION_LOGOUT_DEVICE));
        return response(['message' => "Token removed from device"]);
    }



    /**
     * Login device
     *
     * @authenticated
     * @bodyParam uuid uuid required The uuid of the Device.
     * @response status=200 scenario="success" {"message": "alert send."}
     * @response status=404 scenario="device not found" {"message": "Device not found"}
     * @response status=422 scenario="bad request" {"message": "Bad request"}
     * @response status=405 scenario="Unauthorized request" {"message": "Unauthorized request"}
     * @apiResourceModel App\Models\Device
     */
    public function updateTokenOnDevice(string $uuid)
    {
        $device = Device::findOrFail($uuid);
        dispatch(new APNSToken($device->device_token,self::ACTION_REFRESH_TOKEN,AuthenticationController::generateNewJWTToken(Auth::user())));
        return response(['message' => "Token updated on device"]);
    }

    /**
     * Refresh playlists on device
     *
     * @authenticated
     * @bodyParam uuid uuid required The uuid of the Device.
     * @response status=200 scenario="success" {"message": "alert send."}
     * @response status=404 scenario="device not found" {"message": "Device not found"}
     * @response status=422 scenario="bad request" {"message": "Bad request"}
     * @response status=405 scenario="Unauthorized request" {"message": "Unauthorized request"}
     * @apiResourceModel App\Models\Device
     */
    public function updatePlaylistsOnDevice(Request $request, string $uuid)
    {
        $device = Device::findOrFail($uuid);
        $validated = $this->validate($request,[
            'playlists' => 'required|array',
            'playlists.*' => 'required|string|starts_with:pl.'
        ]);
        dispatch(new APNSData($device->device_token,self::ACTION_REFRESH_PLAYLISTS, $validated['playlists']));
        return response(['message' => "playlists refreshed on device"]);
    }

    private function alertValidator($request){
        return $this->validate($request,[
            'title' => 'required|string',
            'body' => 'required|string'
        ]);
    }

}
