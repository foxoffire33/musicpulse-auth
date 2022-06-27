<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Auth\Access\Gate;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UsersController extends Controller
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
        return User::paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * Summary of trashed users
     *
     * @authenticated
     */
    public function trashed()
    {
        return User::onlyTrashed()->paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * Force delete device
     *
     * @authenticated
     * @response status=200 scenario="success" {"message": "Deleted Successfully"}
     * @response status=404 scenario="not found" {"message": "User not found"}
     */
    public function forceDelete(string $id)
    {
        if ((User::withTrashed()->findOrFail($id))->forceDelete())
            return response(['message' => 'Deleted Successfully'], 200);
    }

    /**
     * Restore deleted user
     *
     * @authenticated
     * @response status=200 scenario="success" {"message": "Restore successfully"}
     */
    public function restore(string $id){
        if ((User::withTrashed()->findOrFail($id))->restore())
            return response(['message' => 'Restored Successfully'], 200);

        return response(['message' => 'Deleted user not found'], 404);
    }
}