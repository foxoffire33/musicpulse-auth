<?php

namespace App\Jobs;

class APNSData extends Job
{

    private array $data;

    private $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'apns-push-type: background'
    ];

    private array $guzzleOptions;

    private string $deviceToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $deviceToken, string $action, array $data = [])
    {
        //load class variables
        $this->deviceToken = $deviceToken;
        $this->data = ['aps' => ['content-available' => 1],'action' => $action,'data' => $data];

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
