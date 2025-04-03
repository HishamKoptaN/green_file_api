<?php

return [
    'firebase' => [
        'credentials' => env('FIREBASE_CREDENTIALS_PATH', storage_path('app/firebase/firebase_credentials.json')),
    ],
    'fcm' => [
    'key' => env('FCM_SERVER_KEY'),
],

];
