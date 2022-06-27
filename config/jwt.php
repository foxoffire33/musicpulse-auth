<?php

return [
    'key' => env('JWT_KEY'),
    'aud' => env('JWT_AUD'),
    'alg' => env('JWT_ALG'),
    'iss' => env('JWT_ISS'),
    'payload' => [
        "iss" => env('JWT_ISS'),
        ]
];