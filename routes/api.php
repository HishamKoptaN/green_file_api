<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatusesApiController;
use App\Http\Controllers\Api\PostsApiController;
use App\Http\Controllers\Api\PostCmntsApiController;
use App\Http\Controllers\Api\JobsApiController;

Route::any(
    '/statuses',
    [
        StatusesApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/posts/{id?}',
    [
        PostsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/post-cmnts/{id?}',
    [
        PostCmntsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/jobs/{id?}',
    [
        JobsApiController::class,
        'handleReq',
    ],
);

Route::middleware('auth:sanctum')->group(
    function () {
        //         Route::any(
        //             '/services',
        //             [
        //                 ServicesApiController::class,
        //                 'handleReq',
        //             ],
        //         );
        //         Route::any(
        //             '/notifications',
        //             [
        //                 NotificationsApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/profile',
        //             [
        //                 ProfileApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/projects',
        //             [
        //                 ProjectsApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/jobs',
        //             [
        //                 JobsApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/courses',
        //             [
        //                 CoursesApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/ppinion-poll',
        //             [
        //                 OpinionPollsApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/news',
        //             [
        //                 NewsApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/companies',
        //             [
        //                 CompaniesApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/training',
        //             [
        //                 TrainingApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/services',
        //             [
        //                 ServicesApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/opinion',
        //             [
        //                 OpinionApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
        //         Route::any(
        //             '/balance',
        //             [
        //                 BalanceApiController::class,
        //                 'handleRequest',
        //             ],
        //         );
    },
);
Route::get(
    '/test',
    function () {
        return "test app";
    },
);
