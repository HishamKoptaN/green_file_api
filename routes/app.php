<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\App\DashAppController;
use App\Http\Controllers\App\BuySellAppController;
use App\Http\Controllers\App\SupportChatAppController;
use App\Http\Controllers\App\PlansAppController;
use App\Http\Controllers\App\DepositsAppController;
use App\Http\Controllers\App\WithdrawAppController;
use App\Http\Controllers\App\AccountsAppController;
use App\Http\Controllers\App\NotificationsAppController;
use App\Http\Controllers\App\TransferAppController;
use App\Http\Controllers\App\TasksAppController;
use App\Http\Controllers\App\TransactionsAppController;
use App\Http\Controllers\App\ProfileAppController;
use App\Http\Controllers\App\WithdrawsDepositsAppController;
use App\Models\User;

Route::post(
    '/chat',
    [
        ChatController::class,
        'sendMessage',
    ],
);
Route::any(
    '/dash',
    [
        DashAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/profile',
    [
        ProfileAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/plans-rates',
    [
        PlansAppController::class,
        'getPlansRates',
    ],
);
Route::any(
    '/plans/{id?}',
    [
        PlansAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/accounts',
    [
        AccountsAppController::class,
        'handleAccounts',
    ],
);
Route::any(
    '/support',
    [
        SupportChatAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/deposits',
    [
        DepositsAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/deposit-rates',
    [
        DepositsAppController::class,
        'getEmployeeAccounts',
    ],
);
Route::any(
    '/employee-accounts',
    [
        DepositsAppController::class,
        'getEmployeeAccounts',
    ],
);
//
Route::any(
    '/withdraws-deposits',
    [
        WithdrawsDepositsAppController::class,
        'handleWithdrawsDeposits',
    ],
);
Route::any(
    '/withdraws',
    [
        WithdrawAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/withdraw-rates',
    [
        WithdrawAppController::class,
        'getWithdrawRates',
    ],
);
Route::any(
    '/transfer/{account_number?}',
    [
        TransferAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/notifications',
    [
        NotificationsAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/buy-sell/{id?}',
    [
        BuySellAppController::class,
        'handleRequest',
    ],
);
Route::any(
    '/tasks/{id?}',
    [
        TasksAppController::class,
        'handleRequest',
    ],
);
Route::get(
    '/task/details/{id}',
    [
        TasksAppController::class,
        'getTaskDetails',
    ],
);
Route::get(
    '/trans',
    [
        TransactionsAppController::class,
        'handleRequest',
    ],
);
Route::get(
    '/settings',
    function () {
        $plans_admin = User::where(
            'role',
            5,
        )->first();
        if (!$plans_admin) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No active employee found'
                ],
                404,
            );
        }
        return response()->json(
            [
                'status' => true,
                'account_info ' => $plans_admin->account_info
            ],
            200,
        );
    },
);
Route::get(
    '/test',
    function () {
        return "test app";
    },
);
