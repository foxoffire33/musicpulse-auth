<?php

namespace App\Jobs;

use GuzzleHttp\Client;

class APSAlert extends Job
{
    private array $data = [
        'aps' => [
            'alert' => [],
            'sound' => 'default'
        ]
    ];

    private $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'apns-push-type: alert'
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
        $this->data['aps']['alert'] = $alert;

        //add dynamic headers
        $this->headers[] = 'apns-topic: ' . config('apns.topic');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->executeRequest($this->headers,$this->data,$this->deviceToken);
    }
}
