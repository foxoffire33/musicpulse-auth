<?php

namespace App\Http\Controllers\Admin;

use App\Models\Device;

/**
 * @group Devices management
 */
class DevicesController extends Controller
{
    public function __construct()
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
    }
    /**
     * Summary of all devices
     *
     * @authenticated
     */
    ///Admin functions
    public function index()
    {
        return Device::paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * Summary of trashed devices
     *
     * @authenticated
     */
    public function trashed()
    {
        return Device::onlyTrashed()->paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * Force delete device
     *
     * @authenticated
     * @response status=200,201 scenario="success" {"message": "Deleted Successfully"}
     * @response status=404 scenario="apparaat niet gevonden" {"message": "Apparaat niet gevonden"}
     * @response status=405 scenario="Niet geautoriseerd" {"message": "Niet geautoriseerd"}
     */

    public function forceDelete(string $id)
    {
        if ((Device::findOrFail($id))->forceDelete())
            return response(['message' => 'Deleted Successfully'], 200);
    }

    /**
     * Restore deleted device
     *
     * @authenticated
     * @response status=200,201 scenario="success" {"message": "Restore successfully"}
     */
    public function restore(string $id){
        if ((Device::findOrFail($id))->restore())
            return response(['message' => 'Restore successfully'], 200);
    }
}