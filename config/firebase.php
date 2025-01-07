<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$factory = (new Factory)
    ->withServiceAccount(
        base_path(
            env(
                'FIREBASE_CREDENTIALS',
            ),
        ),
    );

$firebase = $factory->createAuth();
