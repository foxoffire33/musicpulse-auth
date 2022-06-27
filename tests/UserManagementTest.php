<?php

use App\Models\Device;
use App\Models\User;

class UserManagementTest extends TestCase
{
    /**
     * testen of ik een lijst kan op vragen van alle users die aan mijn account zijn gelikt.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testIndexAsGuest()
    {
        $this->json('GET', '/users')->seeStatusCode(401);
    }

    public function testIndexAAuthenticated()
    {
        $user = \App\Models\User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/users', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testIndexAsAdmin()
    {
        $user = \App\Models\User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/users', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * testen of ik een lijst kan op vragen van alle users. Je hebt hiervoor admin rechten nodig.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testAdminIndexAsGuest()
    {
        $this->json('GET', '/users/admin')->seeStatusCode(401);
    }

    public function testAdminIndexAuthenticated()
    {
        $user = \App\Models\User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/users/admin', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testAdminIndexAsAdmin()
    {
        $user = \App\Models\User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/users/admin', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * Testen of ik de gegevens van 1 user kan ophalen.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testShowAsGuest()
    {
        $this->json('GET', '/devices/' . $device = Device::take(1)->first()->id)->seeStatusCode(401);
    }

    public function testShowAsOwner()
    {
        $user = User::where('role', User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $this->json('GET', '/users/' . $user->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testShowAsAuthenticated()
    {
        $user = User::where('role', User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $orderUser = User::first();
        $this->json('GET', '/users/' . $orderUser->id, [], ['Authorization' => "Bearer " . $generatedToken]);
    }

    /**
     * Testen of de validateie goed werkt en alle correct wordt opgeslagen.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testCreateAsGuest()
    {
        $device = User::factory()->create();
        $this->json('POST', '/users/', $device->attributesToArray())->seeStatusCode(401);
    }

    public function testCreateAsAuthenticated()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $this->json('POST', '/users/', $device->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testCreateAsAdmin()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $user = User::factory()->make();
        $postRequest = $user->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $postRequest['password'] = 'asdasd';

        $this->json('POST', '/users/', $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(201);
    }

    public function testCreateAsAdminAddRole()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $user = User::factory()->make();
        $postRequest = $user->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $postRequest['password'] = 'asdasd';
        $postRequest['role'] = User::USER_ROLE_PARTNER;

        $this->json('POST', '/users/', $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(201);
    }

    public function testCreateInvalidData()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $device = Device::factory()->create();
        $this->json('POST', '/users/', $device->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(422);
    }

    /**
     * Testen of de validateie goed werkt en alle correct wordt opgeslagen.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */

    public function testUpdateAsGuest()
    {
        $users = User::factory()->count(2)->create();
        $this->json('PUT', '/users/' . $users[0]->id, $users[0]->attributesToArray())->seeStatusCode(401);
    }

    public function testUpdateAsAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $orderUser = User::where('id', '!=', $user->id)->first();

        $newUser = User::factory()->make();
        $postRequest = $newUser->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $postRequest['password'] = 'asdasd';
        $postRequest['role'] = User::USER_ROLE_PARTNER;
        $this->json('PUT', '/users/' . $orderUser->id, $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testUpdateAsAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $orderUser = User::where('id', '!=', $user->id)->first();

        $newUser = User::factory()->make();
        $postRequest = $newUser->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $postRequest['password'] = 'asdasd';
        $postRequest['role'] = User::USER_ROLE_PARTNER;
        $this->json('PUT', '/users/' . $orderUser->id, $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testUpdateAsOwner()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $newUser = User::factory()->make();
        $postRequest = $newUser->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $postRequest['password'] = 'asdasd';
        $postRequest['role'] = User::USER_ROLE_PARTNER;
        $this->json('PUT', '/users/' . $user->id, $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testUpdateInvalidData()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $newUser = User::factory()->make();
        $postRequest = $newUser->makeHidden(['id', 'updated_at', 'created_at'])->attributesToArray();
        $this->json('PUT', '/users/' . $user->id, $postRequest, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(422);
    }

    /**
     * Testen of het user correct wordt verwijderd via soft delete.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */

    public function testDeleteAsGuest()
    {
        $user = User::factory()->create();
        $this->json('DELETE', '/users/' . $user->id, [])->seeStatusCode(401)->seeJson(['message' => 'Unauthorized.']);
    }

    public function testDeleteAsAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $newUser = User::factory()->create();
        $this->json('DELETE', '/users/' . $newUser->id, ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(401)->seeJson(['message' => 'Unauthorized.']);
    }

    public function testDeleteAsAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $newUser = User::factory()->create();
        $this->json('DELETE', '/users/' . $newUser->id,[], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testDeleteAsOwner()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $this->json('DELETE', '/users/' . $user->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * Testen of het net verwijderde item in de trashed zit.
     * Dit kan alleen een admin user.
     */
    public function testTrashedAsGuest()
    {
        $this->json('GET', '/users/admin/trashed')->seeStatusCode(401);
    }

    public function testTrashedAAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $this->json('GET', 'users/admin/trashed', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testTrashedAAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $this->json('GET', 'users/admin/trashed', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * Testen of ik een user weer uit de trashed kan halen.
     * Dit kan alleen een admin user.
     */
    public function testRestoreTrashedGuest()
    {
        $user = User::factory()->create();
        $user->delete();
        $this->json('POST', '/devices/admin/' . $user->id . '/restore')->seeStatusCode(401);
    }

    public function testRestoreTrashedAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $user = User::factory()->create();
        $user->delete();
        $this->json('POST', '/users/admin/' . $user->id . '/restore', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);

    }

    public function testRestoreTrashedAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $user = User::factory()->create();
        $userID = $user->id;
        $user->delete();
        $this->json('POST', '/users/admin/' . $userID . '/restore', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * Testen of het lukt om een devic evoledig uit de database te verwijderen.
     * Dit kan alleen een admin user.
     */
    public function testForceDeleteGuest()
    {
        $user = User::factory()->create();
        $this->json('DELETE', '/users/admin/' . $user->id . '/force-delete')->seeStatusCode(401);
    }

    public function testForceDeleteAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $userDeleted = User::factory()->create();
        $userDeleted->delete();
        $this->json('DELETE', '/users/admin/' . $userDeleted->id . '/force-delete', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testForceDeleteAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $userDeleted = User::factory()->create();
        $userID = $userDeleted->id;
        $userDeleted->delete();
        $this->json('DELETE', '/users/admin/' . $userID . '/force-delete', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }
}
