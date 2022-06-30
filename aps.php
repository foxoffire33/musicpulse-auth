<?php

const AUTH_KEY_PATH = './private/AuthKey_C5U854UST5.p8';
const AUTH_KEY_ID = 'C5U854UST5';
const TEAM_ID = 'K24WJ2H47L';
const APNS_TOPIC = 'ios.nl.delaparra-services.MusicPulse';

// Setup the payload
$payload = [
    'aps' => [
        'alert' => [
            'title' => 'This is the notification.',
            'body' => "This is a test notification sent with a PHP script",
        ],
        'sound' => 'pulse-notification.aiff'
    ],
];

var_dump(json_encode($payload));exit;

//// Create The JWT
$header = base64_encode(json_encode(['alg' => 'ES256', 'kid' => AUTH_KEY_ID]));
$claims = base64_encode(json_encode(['iss' => TEAM_ID, 'iat' => time()]));
$pkey = openssl_pkey_get_private('file://' . AUTH_KEY_PATH);

openssl_sign("$header.$claims", $signature, $pkey, 'sha256');

$signed = base64_encode($signature);
$signedHeaderData = "$header.$claims.$signed";

var_dump(json_encode(['alg' => 'ES256', 'kid' => AUTH_KEY_ID]));
var_dump($header);
var_dump(json_encode(['iss' => TEAM_ID, 'iat' => time()]));
exit;
//var_dump($signedHeaderData);

//Setup curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['apns-topic: ' . APNS_TOPIC, 'authorization: bearer ' . $signedHeaderData, 'apns-push-type: alert']);
//Setting up URL
$token = $argv[1];
$url = "https://api.development.push.apple.com/3/device/$token";
//Making the call
curl_setopt($ch, CURLOPT_URL, "{$url}");
$response = curl_exec($ch);
// DEAL WITH IT ('it' being errors)
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);