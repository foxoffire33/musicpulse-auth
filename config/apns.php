<?php

return [
    'key' => env('AUTH_KEY_PATH'),
    'auth_key' => env('AUTH_KEY_ID'),
    'team' => env('TEAM_ID'),
    'topic' => env('APNS_TOPIC'),
    'sound' =>  env('NOTIFICATION_SOUND','default')
];