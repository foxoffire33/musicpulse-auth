<?php

use App\Models\User;
use Firebase\JWT\JWT;

class AuthenticationTest extends TestCase
{
    /**
     * Er moet getest worden of de rate limiting werkt, na 5 keer een login request te hebben gestuurd moet je een fout melding krijgen.
     */
    public function testRateLimiting(){
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);

        //rate limiting, status code 429 krijg je als je te veel aanvragen doet, het limit is gezet op 5 keer per minute
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null,429);
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null, 429);
    }

    /**
     *  Checken of het inloggen met email address en password.
     */
    public function testBasicAuthentication()
    {
        $this->authenticate("admin@dxmusic.nl", 'asdasd', null);
        $this->authenticate("partner@dxmusic.nl", 'asdasd', null);

        $device = \App\Models\Device::take(1)->first();
        $this->authenticate("device@dxmusic.nl", 'asdasd', $device->id);
        $this->authenticate("device@dxmusic.nl", 'asdasd', null, 422);
    }


    /**
     * Testen of het verlenegen van je token werkt.
     */
    public function testTokenAuthentication()
    {
        //get user object and renerate valid token
        $user = User::where('role', User::USER_ROLE_ADMIN)->first();
        $generatedToken = \App\Http\Controllers\AuthenticationController::generateNewJWTToken($user);

        //try to renew token
        $this->json('GET', '/jwt', [], ['Authorization' => "Bearer " . $generatedToken])->seeStatusCode(200);
        $this->assertTrue($this->isTokenValied($this->response->original['token']));
    }

    public function testTokenAuthenticationWithoutHeader(){
        //try to renew token without Authorization header
        $this->json('GET', '/jwt',[],[])->seeStatusCode(401);
    }

    private function authenticate(string $email, string $password, string|null $deviceToken, int $responseStatus = 200): void
    {
        $this->json('POST', '/', ['email' => $email, 'password' => $password, 'deviceToken' => $deviceToken]);
       if($this->getStatus() == 500)
           dd($this->response);

        if (isset($this->response->original['token']))
             $this->assertTrue($this->isTokenValied($this->response->original['token']));
    }

    private function isTokenValied(string $token): bool
    {
        try {
            JWT::decode($token, config('jwt.key'), [config('jwt.alg')]);
            return true;
        } catch (\Exception $e) {
        }
        return false;
    }
}
