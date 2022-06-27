<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @group Devices management
 * APIs for managing devices and comulicateing with devices.
 */
class DevicesController extends Controller
{
    /**
     * Summary of all user devices
     *
     * @authenticated
     * @response status=405 scenario="Niet geautoriseerd" {"message": "Niet geautoriseerd"}
     * @response status 200
     * [{
     * "deviceID": "0FDF668C-074A-4A62-BCEC-3CBEDE25C524",
     * "deviceName": "Luxe mp3 speler",
     * "deviceToken": "e6b4953f2f5dc9a409506a2932c9abcf62da7751ca23ba23f734c07c315",
     * "deviceOS": "IOS",
     * "OSVersion": 15.2,
     * "AppVersion": 1.2,
     * }]
     */
    public function index()
    {
        return Device::whereHas('users', fn($builder) => $builder->where(['user_id' => Auth::id()]))->paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * View user device
     *
     * @authenticated
     * @urlParam id uuid required
     * @response status=201 scenario="success" {"message": "Created Successfully"}
     * @response status=404 scenario="not found" {"message": "Niet gevonden"}
     * @response status=405 scenario="unauthirzed" {"message": "Unauthirzed"}
     * @response status=422 scenario="error" {"message": "The given data was invalid"}
     * @response status 200
     * {
     * "deviceID": "0FDF668C-074A-4A62-BCEC-3CBEDE25C524",
     * "deviceName": "Luxe mp3 speler",
     * "deviceToken": "e6b4953f2f5dc9a409506a2932c9abcf62da7751ca23ba23f734c07c315",
     * "deviceOS": "IOS",
     * "OSVersion": 15.2,
     * "AppVersion": 1.2,
     * },
     */
    public function show(string $id)
    {
        $device = Device::findOrFail($id);
        Gate::authorize('manage-devices',$device);
        return $device;
    }

    /**
     * Create new device for user
     *
     * @bodyParam id uuid required Het unique id die door de fabrikant gegenereerd is Example: 0FDF668C-074A-4A62-BCEC-3CBEDE25C524
     * @bodyParam device_name string required De naam die de gebruiker aan eht Appraat heeft gegeven Example: Luxe mp3 speler
     * @bodyParam device_token string required De token voor push notificaties Example: 12402e6b4953f2f5dc9a409506a2932c9abcf62da7751ca23ba23f734c07c315
     * @bodyParam device_os string required Het bestuurings systeem dat het Apprraat draait Example: IOS
     * @bodyParam os_version double required de versie van het bestuuringssysteem Example: 15.2
     * @bodyParam app_version double required de versie van de app, Example: 1.2
     * @response status=201 scenario="success" {"message": "Created Successfully"}
     * @response status=404 scenario="not found" {"message": "Niet gevonden"}
     * @response status=405 scenario="unauthirzed" {"message": "Unauthirzed"}
     * @response status=422 scenario="error" {"message": "The given data was invalid"}
     * @authenticated
     */
    public function create(\Illuminate\Http\Request $request)
    {
        if (Device::updateOrCreate(['device_token' => $request->get('device_token')],$this->validateRequest($request)))
            return response('Save Successfully', 201);

        return response('Not saved', 422);
    }

    /**
     * Update existing device
     *
     * @urlParam id uuid required
     * @bodyParam device_name string required De naam die de gebruiker aan eht Appraat heeft gegeven Example: Luxe mp3 speler
     * @bodyParam device_token string required De token voor push notificaties Example: 12402e6b4953f2f5dc9a409506a2932c9abcf62da7751ca23ba23f734c07c315
     * @bodyParam device_os string required Het bestuurings systeem dat het Apprraat draait Example: IOS
     * @bodyParam os_version double required de versie van het bestuuringssysteem Example: 15.2
     * @bodyParam app_version double required de versie van de app, Example: 1.2
     * @response status=201 scenario="success" {"message": "Updated Successfully"}
     * @response status=404 scenario="not found" {"message": "Niet gevonden"}
     * @response status=405 scenario="unauthirzed" {"message": "Unauthirzed"}
     * @response status=422 scenario="error" {"message": "The given data was invalid"}
     * @authenticated
     */
    public function update(Request $request, string $id)
    {
        $device = Device::findOrFail($id);
        Gate::authorize('manage-devices',$device);

        $request->request->add(['id' => $id]);

        $device->update($this->validateRequest($request));
        return response('Updated Successfully', 200);
    }

    /**
     * Delete an device
     *
     * @urlParam id uuid required
     * @authenticated
     * @response status=200,201 scenario="success" {"message": "Deleted Successfully"}
     * @response status=404 scenario="apparaat niet gevonden" {"message": "Apparaat niet gevonden"}
     * @response status=405 scenario="Niet geautoriseerd" {"message": "Niet geautoriseerd"}
     */
    public function destroy(string $id)
    {
        $device = Device::findOrFail($id);
        Gate::authorize('manage-devices',$device);


        if ($device->delete())
            return response(['message' => 'Deleted Successfully'], 200);
    }

    private function validateRequest(Request $request)
    {
        return $this->validate($request, [
            'id' => 'uuid',
            'device_name' => 'required_without:id|string',
            'device_token' => 'required_without:id|string',
            'device_os' => 'required_without:id|string',
            'os_version' => 'required_without:id|numeric',
            'app_version' => 'required_without:id|string'
        ]);
    }


}
