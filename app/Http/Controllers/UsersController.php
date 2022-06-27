<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UsersController extends Controller
{
    /**
     * Summary of all users
     *
     * @authenticated
     * @response status=422 scenario="Ongeldig aanvraag" {"message": "Ongeldige aanvraag"}
     * @response status=405 scenario="Niet toegestaan" {"message": "Niet toegestaan"}
     */
    public function index()
    {
        return User::paginate(Controller::PAGINATE_SIZE);
    }

    /**
     * View user
     *
     * @authenticated
     * @response status=404 scenario="gebruiker niet gevonden" {"message": "Gebruiker niet gevonden"}
     * @response status=422 scenario="Ongeldig aanvraag" {"message": "Ongeldige aanvraag"}
     * @response status=405 scenario="Niet toegestaan" {"message": "Niet toegestaan"}
     */
    public function show(string $uuid)
    {
        $user = User::findOrFail($uuid);
        Gate::authorize('manage-users', $user);

        return $user;
    }

    /**
     * Create new user
     *
     * @authenticated
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam role int
     * @response status=201 scenario="Succesvol opgeslagen" {"message": "Gebruiker opgeslagen"}
     * @response status=404 scenario="gebruiker niet gevonden" {"message": "Gebruiker niet gevonden"}
     * @response status=422 scenario="Ongeldig aanvraag" {"message": "Ongeldige aanvraag"}
     * @response status=405 scenario="Niet geautoriseerd" {"message": "Niet geautoriseerd"}
     */
    public function create(Request $request, User $user)
    {
        Gate::authorize('admin');
        $user = new User($this->validateRequest($request));

        if ($user->save())
            return response(['message' => 'Save Successfully'], 201);

        return response(['message' => 'Not saved'], 422);
    }

    /**
     * Update user
     *
     * @authenticated
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam role int
     * @response status=201 scenario="Succesvol opgeslagen" {"message": "Gebruiker opgeslagen"}
     * @response status=404 scenario="gebruiker niet gevonden" {"message": "Gebruiker niet gevonden"}
     * @response status=422 scenario="Ongeldig aanvraag" {"message": "Ongeldige aanvraag"}
     * @response status=405 scenario="Niet toegestaan" {"message": "Niet toegestaan"}
     */
    public function update(Request $request, string $uuid)
    {
        $user = User::findOrFail($uuid);
        Gate::authorize('manage-users', $user);
        $validated = $this->validateRequest($request);
        if ($user->update($validated))
            return response(['message' => 'Updated Successfully'], 200);
    }

    /**
     * Delete user
     *
     * @authenticated
     * @response status=201 scenario="Succesvol opgeslagen" {"message": "Gebruiker opgeslagen"}
     * @response status=404 scenario="gebruiker niet gevonden" {"message": "Gebruiker niet gevonden"}
     * @response status=422 scenario="Ongeldig aanvraag" {"message": "Ongeldige aanvraag"}
     * @response status=405 scenario="Niet toegestaan" {"message": "Niet toegestaan"}
     */
    public function delete(string $uuid)
    {

        $user = User::findOrFail($uuid);
        Gate::authorize('manage-users', $user);

        if ($user->delete())
            return response('Deleted Successfully', 200);
    }

    private function validateRequest(Request $request)
    {
        return $this->validate($request, [
            'email' => 'required_without:password|email|unique:users', //email address must be unique
            'password' => 'required|string',
            'created_by,updated_by,deleted_by' => 'uuid|exists:users,id', //created by user must already exist
            'role' => Rule::in([ //check for supported user roles
                User::USER_ROLE_ADMIN,
                User::USER_ROLE_USER,
            ]),
        ]);
    }
}
