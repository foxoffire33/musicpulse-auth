<?php

namespace App\Jobs;

use GuzzleHttp\Client;

class APSAlert extends Job
{
    private array $payload = [
        'aps' => [
            'alert' => [],
            'sound' => ''
        ]
    ];

    private string $deviceToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $deviceToken, array $alert)
    {
        //load class variables
        $this->deviceToken = $deviceToken;
        $this->payload['aps']['alert'] = $alert;
        $this->payload['aps']['sound'] = config('apns.sound');
    }

    public function handle()
    {
        $header = base64_encode(json_encode([
            'alg' => 'ES256',
            'kid' => config('apns.auth_key')
        ]));

        $claims = base64_encode(json_encode([
            'iss' => config('apns.team'),
            'iat' => time()
        ]));

        $pkey = openssl_pkey_get_private('file://' . config('apns.key'));
        openssl_sign("$header.$claims", $signature, $pkey, 'sha256');

        $signed = base64_encode($signature);
        $signedHeaderData = "$header.$claims.$signed";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_POSTFIELDS,

            json_encode($this->payload));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['apns-topic: ' . config('apns.topic'), 'authorization: bearer ' . $signedHeaderData, 'apns-push-type: alert']);

        $url = "https://api.development.push.apple.com/3/device/$this->deviceToken";
        curl_setopt($ch, CURLOPT_URL, "{$url}");
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
}
