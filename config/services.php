<?php

return [
    'firebase' => [
        'credentials' => env('FIREBASE_CREDENTIALS_PATH', storage_path('app/firebase/firebase_credentials.json')),
    ],
    'fcm' => [
        'key' => env('FCM_SERVER_KEY'),
    ],
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],
];
