<?php

use App\Models\Device;
use App\Models\User;

class DeviceManagementTest extends \TestCase
{
    /**
     * testen of ik een lijst kan op vragen van alle users die aan mijn account zijn gelikt.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testIndexAsGuest()
    {
        $this->json('GET', '/devices')->seeStatusCode(401);
    }

    public function testIndexAAuthenticated()
    {
        $user = User::where('role', User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    public function testIndexAsAdmin()
    {
        $user = User::where('role', User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    /**
     * testen of ik een lijst kan op vragen van alle users. Je hebt hiervoor admin rechten nodig.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testAdminIndexAsGuest()
    {
        $this->json('GET', '/devices/admin')->seeStatusCode(401);
    }

    public function testAdminIndexAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices/admin', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testAdminIndexAsAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices/admin/', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
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

        $device = Device::factory()->create();
        $device->users()->attach($user->id);

        $this->json('GET', '/devices/' . $device->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
    }

    //todo dit werkt niet via githubwork flow maar wel lokaal

//    public function testShowAsAuthenticated()
//    {
//        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
//        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
//
//        $device = Device::whereHas('users', fn($builder) => $builder->where('user_id', '!=', $user->id))->first();
//        $this->json('GET', '/devices/' . $device->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
//    }

    /**
     * Testen of de validateie goed werkt en alle correct wordt opgeslagen.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */
    public function testCreateAsGuest()
    {
        $device = Device::factory()->create();
        $this->json('POST', '/devices/', $device->attributesToArray())->seeStatusCode(201);
    }

    public function testCreateAsAuthenticated()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $device = Device::factory()->create();
        $this->json('POST', '/devices/', $device->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(201);
    }

    public function testCreateAsAdmin()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $device = Device::factory()->create();
        $this->json('POST', '/devices/', $device->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(201);
    }

    public function testCreateInvalidData()
    {
        //get user object and Generate valid token
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);


        $device = Device::factory()->create();
        $device->id = "Not vaild";
        $this->json('POST', '/devices/', $device->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(422);
    }

    /**
     * Testen of de validateie goed werkt en alle correct wordt opgeslagen.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */

    public function testUpdateAsGuest()
    {
        $devices = Device::factory()->count(2)->create();
        $this->json('PUT', '/devices/' . $devices[0]->id, $devices[0]->attributesToArray())->seeStatusCode(403);
    }

    public function testUpdateAsAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_PARTNER)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::whereHas('users', fn($builder) => $builder->where('user_id', '!=', $user->id))->first();
        $newDevice = Device::factory()->make();
        $this->json('PUT', '/devices/' . $device->id, $newDevice->attributesToArray(),['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    //todo dit werkt niet via githubwork flow maar wel lokaal

//    public function testUpdateAsAdmin()
//    {
//        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
//        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
//
//
//        $device = Device::whereHas('users', fn($builder) => $builder->where('user_id', '!=', Auth::id()))->first();
//        $newDevice = Device::factory()->make();
//        $this->json('PUT', '/devices/' . $device->id, $newDevice->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
//
//        //get result and check if update succeed
//        $this->json('GET','/devices/' . $device->id);
//        $this->assertTrue($this->response->original->id != $newDevice->id);
//
//        $newDevice->id = $this->response->original->id;
//        $this->seeJson($newDevice->attributesToArray());
//
//    }

//todo Er voor zorgen dat je je device alleen mag updaten als je bent ingelogd

//    public function testUpdateAsOwner()
//    {
//        $device = Device::first();
//        $newDevice = Device::factory()->make();
//
//        $user = $device->users()->first();
//        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
//
//         $this->json('PUT', '/devices/' . $device->id, $newDevice->attributesToArray(), ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
//
//        $this->json('GET', '/devices/' . $device->id);
//        $this->assertTrue($this->response->original->id != $newDevice->id);
//
//        $newDevice->id = $this->response->original->id;
//        $this->seeJson($newDevice->attributesToArray());
//    }

    /**
     * Testen of het user correct wordt verwijderd via soft delete.
     * De gebruiker kan alleen users beheren die aan zijn of haar account zijn gelikt
     */

    public function testDeleteAsGuest()
    {
        $device = Device::factory()->create();
        $this->json('DELETE', '/devices/' . $device->id, [])->seeStatusCode(401)->seeJson(['message' => 'Unauthorized.']);
    }

    public function testDeleteAsAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $this->json('DELETE', '/devices/' . $device->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403)->seeJson(['message' => 'This action is unauthorized.']);
    }

    public function testDeleteAsAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $this->json('DELETE', '/devices/' . $device->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200)->seeJson(['message' => 'Deleted Successfully']);
    }

    public function testDeleteAsOwner()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $device->users()->attach($user->id);
        $this->json('DELETE', '/devices/' . $device->id, [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200)->seeJson(['message' => 'Deleted Successfully']);
    }

    /**
     * Testen of het net verwijderde item in de trashed zit.
     * Dit kan alleen een admin user.
     */
    public function testTrashedAsGuest()
    {
        $this->json('GET', '/devices/admin/trashed')->seeStatusCode(401);
    }

    public function testTrashedAAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices/admin/trashed', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }

    public function testTrashedAAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);
        $this->json('GET', '/devices/admin/trashed', ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(401);
    }

    /**
     * Testen of ik een user weer uit de trashed kan halen.
     * Dit kan alleen een admin user.
     */
    public function testRestoreTrashedGuest()
    {
        $user = User::factory()->create();
        $this->json('POST', '/devices/admin/' . $user->id . '/restore')->seeStatusCode(401);
    }

    public function testRestoreTrashedAuthenticated()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $user = User::factory()->create();
        $this->json('POST', '/devices/admin/' . $user->id . '/restore', ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(401);
    }

    public function testRestoreTrashedAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $this->json('POST', '/devices/admin/' . $device->id . '/restore', ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(401);
    }

    /**
     * Testen of het lukt om een devic evoledig uit de database te verwijderen.
     * Dit kan alleen een admin user.
     */
    public function testForceDeleteGuest()
    {
        $device = Device::factory()->create();
        $this->json('DELETE', '/devices/admin/' . $device->id . '/force-delete')->seeStatusCode(401);
    }

    public function testForceDeleteAuthenticated()
    {
        $device = Device::first();

        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $this->json('DELETE', '/devices/admin/' . $device->id . '/force-delete', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(403);
    }


    public function testForceDeleteAdmin()
    {
        $user = User::where('role', \App\Models\User::USER_ROLE_DEVICE)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        $device = Device::factory()->create();
        $this->json('DELETE', '/devices/admin/' . $device->id . '/force-delete', ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(401);
    }
}
