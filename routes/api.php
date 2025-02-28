<?php

use Illuminate\Support\Facades\Route;
//! social
use App\Http\Controllers\Api\Social\LikeApiController;
use App\Http\Controllers\Api\Social\StatusesApiController;
use App\Http\Controllers\Api\Social\PostsApiController;
use App\Http\Controllers\Api\Social\CmntsApiController;
use App\Http\Controllers\Api\Social\FriendshipsController;
//! freelanceFile
use App\Http\Controllers\Api\FreelanceFile\Projects\ProjectsApiController;
use App\Http\Controllers\Api\JobsApiController;
//! BusinessFile
use App\Http\Controllers\Api\BusinessFile\NewsApiController;
use App\Http\Controllers\Api\BusinessFile\CompanyPostsApiController;
use App\Http\Controllers\Api\BusinessFile\OpinionPollsApiController;
use App\Http\Controllers\Api\BusinessFile\ServicesApiController;
use App\Http\Controllers\Api\BusinessFile\TrainingApiController;
use App\Http\Controllers\FollowController;
//! social
Route::any(
    '/statuses',
    [
        StatusesApiController::class,
        'handleReq',
    ],
);
Route::match(
    [
        'GET',
        'POST',
    ],
    '/posts',
    [
        PostsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/likes/{id?}',
    [
        LikeApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/cmnts/{id?}',
    [
        CmntsApiController::class,
        'handleReq',
    ],
);
//! follows
Route::post('follow/{id}', [FollowController::class, 'follow']);
Route::post('unfollow/{id}', [FollowController::class, 'unfollow']);
Route::get('followers/{user}', [FollowController::class, 'followers']);
Route::get('followings/{user}', [FollowController::class, 'followings']);
//! Friendships
Route::post('friends/request/{id}', [FriendshipsController::class, 'sendRequest']);
Route::post('friends/accept/{id}', [FriendshipsController::class, 'acceptRequest']);
Route::post('friends/reject/{id}', [FriendshipsController::class, 'rejectRequest']);
Route::post('friends/unfriend/{id}', [FriendshipsController::class, 'unfriend']);
Route::get('friends/list', [FriendshipsController::class, 'listFriends']);
Route::get('friends/incoming', [FriendshipsController::class, 'incomingRequests']);
Route::get('friends/outgoing', [FriendshipsController::class, 'outgoingRequests']);
//! Freelance
Route::any(
    '/projects/{id?}',
    [
        ProjectsApiController::class,
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
//! BusinessFil
Route::any(
    '/news/{id?}',
    [
        NewsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/company-posts/{id?}',
    [
        CompanyPostsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/opinion-polls/{id?}',
    [
        OpinionPollsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/services/{id?}',
    [
        ServicesApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/training/{id?}',
    [
        TrainingApiController::class,
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
