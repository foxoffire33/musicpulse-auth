<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Job implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use InteractsWithQueue, Queueable, SerializesModels;

    //todo Curl functies verplaatsen naar helper klasse
    private function setCurlheaders($request, array $headers)
    {
        //set request headers
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HEADER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    }

    private function setCurlSSLOptions($request)
    {
        curl_setopt($request, CURLOPT_SSLCERT, config('apns.cert'));
        curl_setopt($request, CURLOPT_SSLKEY, config('apns.key'));
        curl_setopt($request, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    }

    private function createPostRequest($request, array $data, string $deviceToken,){
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($request, CURLOPT_URL, config('apns.host') . '/3/device/' . $deviceToken);
    }

    private function initRequest(array $headers){
        $request = curl_init();
        $this->setCurlheaders($request, $headers);
        $this->setCurlSSLOptions($request);
        return $request;
    }

    private function executeHandeler($request){
        return curl_exec($request);
    }

    private function deinitRequest($request){
        curl_close($request);
    }

    protected function executeRequest(array $headers, array $data, string $deviceToken) {
        //Init Curl
        $request = $this->initRequest($headers);
        $this->createPostRequest($request, $data, $deviceToken);
        $this->executeHandeler($request);
        $this->deinitRequest($request);
    }

}
