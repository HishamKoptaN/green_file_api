<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\CheckController;
use App\Http\Controllers\Api\OpportunityLookingController;

Route::post(
    '/auth-token',
    [
        SignInController::class,
        'authToken',
    ],
);
Route::post(
    '/check',
    [
        CheckController::class,
        'check',
    ],
);
Route::post(
    '/role',
    [
        SignUpController::class,
        'assignRoleToUser',
    ],
);
Route::get(
    'opportunity-lookings/search',
    [
        OpportunityLookingController::class,
        'search',
    ],
);
Route::any(
    '/sign-up',
    [
        SignUpController::class,
        'handleReq',
    ],
);
// اختبار
Route::get(
    '/test',
    function () {
        return "test auth";
    },
);
