<?php

return [
    'host' => env('APNS_HOST_NAME'),
    'cert' => base_path() . env('CERTIFICATE_FILE_NAME'),
    'key' => base_path() . env('CERTIFICATE_KEY_FILE_NAME'),
    'topic' => env('APNS_TOPIC')
];