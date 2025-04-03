<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Http;

class FirebaseNotificationController extends Controller
{
    protected $firebaseMessaging;
    protected $firebaseApiKey;

    public function __construct()
    {
        $credentialsPath = base_path(
            'storage/app/firebase/firebase_credentials.json',
        );
        if (!file_exists(
            $credentialsPath,
        )) {
            throw new \Exception(
                'Firebase credentials file is missing.',
            );
        }
        $this->firebaseMessaging = (new Factory)
            ->withServiceAccount(
                $credentialsPath,
            )
            ->createMessaging();
        $this->firebaseApiKey = env(
            'FIREBASE_SERVER_KEY',
        );
    }
}
