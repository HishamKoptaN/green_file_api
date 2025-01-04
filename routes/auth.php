<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CheckAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpDashController;
use App\Http\Controllers\Auth\ChangePassController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
// تحقق المستخدم
Route::post(
    '/check',
    [
        CheckAuthController::class,
        'check',
    ],
);
Route::post(
    '/login',
    [
        LoginController::class,
        'login',
    ],
);
Route::post(
    '/sign-up',
    [
        SignUpDashController::class,
        'signUp',
    ],
);
Route::post(
    '/edit-pass',
    [
        ChangePassController::class,
        'edit',
    ],
);
Route::post(
    '/send-password-otp',
    [
        ResetPasswordController::class,
        'sendOtp',
    ],
);
Route::post(
    '/verify-password-otp',
    [
        ResetPasswordController::class,
        'verifyOtp',
    ],
);
Route::post(
    '/reset-password',
    [
        ResetPasswordController::class,
        'resetPassword',
    ],
);
// تأكيد البريد الإلكتروني
Route::post(
    '/add-email',
    [
        EmailVerificationController::class,
        'addEmail',
    ],
);
Route::post(
    '/send-email-otp',
    [
        EmailVerificationController::class,
        'sendEmailOtp',
    ],
);
Route::post(
    '/verify-email-otp',
    [
        EmailVerificationController::class,
        'verifyEmailOtp',
    ],
);
// اختبار
Route::get(
    '/test',
    function () {
        return "test auth";
    },
);
