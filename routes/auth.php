<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CheckController;

Route::post(
    '/auth-token',
    [
        LoginController::class,
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
    '/countries',
    [
        SignUpController::class,
        'countries',
    ],
);
Route::post(
    '/job-seeker-sign-up',
    [
        SignUpController::class,
        'jobSeekerSignUp',
    ],
);
Route::post(
    '/company-sign-up',
    [
        SignUpController::class,
        'companySignUp',
    ],
);


// اختبار
Route::get(
    '/test',
    function () {
        return "test auth";
    },
);
