<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Profile\ProfileApiController;
//! social
use App\Http\Controllers\Api\Social\LikeApiController;
use App\Http\Controllers\Api\Social\StatusesApiController;
use App\Http\Controllers\Api\Social\PostsApiController;
use App\Http\Controllers\Api\Social\CmntsApiController;
use App\Http\Controllers\Api\Social\FriendshipsApiController;
use App\Http\Controllers\Api\Social\FollowerApiController;
//! freelanceFile
use App\Http\Controllers\Api\FreelanceFile\Projects\ProjectsApiController;
use App\Http\Controllers\Api\JobsApiController;
//! BusinessFile
use App\Http\Controllers\Api\BusinessFile\NewsApiController;
use App\Http\Controllers\Api\BusinessFile\CompanyPostsApiController;
use App\Http\Controllers\Api\BusinessFile\OpinionPollsApiController;
use App\Http\Controllers\Api\BusinessFile\ServicesApiController;
use App\Http\Controllers\Api\BusinessFile\TrainingApiController;
use App\Http\Controllers\Api\NotificationsApiController;
use App\Http\Controllers\FirebaseNotificationController;
use App\Http\Controllers\Api\Cvs\CvsApiController;

Route::get(
    '/cv',
    [
        CvsApiController::class,
        'getCv',
    ],
);
Route::post(
    '/cv',
    [
        CvsApiController::class,
        'updateCv',
    ],
);

Route::post(
    '/create-device-group',
    [
        FirebaseNotificationController::class,
        'createDeviceGroup',
    ],
);
Route::any(
    '/notifications',
    [
        NotificationsApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/profile',
    [
        ProfileApiController::class,
        'handleReq',
    ],
);
//! social
Route::any(
    '/statuses',
    [
        StatusesApiController::class,
        'handleReq',
    ],
);
Route::any(
    '/user-statuses',
    [
        StatusesApiController::class,
        'userStatuses',
    ],
);
Route::post(
    '/posts/share',
    [
        PostsApiController::class,
        'sharePost',
    ],
);
//! posts
Route::get(
    '/posts',
    [
        PostsApiController::class,
        'get',
    ],
);
Route::post(
    '/posts',
    [
        PostsApiController::class,
        'create',
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
Route::prefix('follows')->controller(FollowerApiController::class)->group(
    function () {
        Route::post(
            'toggle-follow/{id}',
            'toggleFollow',
        );
        Route::get(
            'followers',
            'followers',
        );
        Route::get(
            'followings',
            'followings',
        );
        Route::get(
            'suggested',
            'suggested',
        );
    },
);
//! Friendships
Route::prefix('friends')->controller(FriendshipsApiController::class)->group(
    function () {
        Route::post(
            '{id}',
            'sendRequest',
        );
        Route::put(
            '{id}/accept',
            'acceptRequest',
        );
        Route::put(
            '{id}/reject',
            'rejectRequest',
        );
        Route::delete(
            '{id}',
            'unfriend',
        );
        Route::get(
            '/',
            'friends',
        );
        Route::get(
            'incoming',
            'incomingRequests',
        );
        Route::get(
            'outgoing',
            'outgoingRequests',
        );
        Route::get(
            'suggested',
            'suggestedFriends',
        );
    },
);
//! Freelance
Route::any(
    '/projects/{id?}',
    [
        ProjectsApiController::class,
        'handleReq',
    ],
);
//! jobs
Route::prefix('jobs')->controller(JobsApiController::class)->group(
    function () {
        Route::get('/', 'get');
        Route::get('/suggested', 'suggested');
        Route::post('/', 'create');
    },
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
//! services
Route::any(
    '/services/{id?}',
    [
        ServicesApiController::class,
        'handleReq',
    ],
);
Route::get(
    '/search-services',
    [
        ServicesApiController::class,
        'search',
    ],
);
//! training
Route::any(
    '/training/{id?}',
    [
        TrainingApiController::class,
        'handleReq',
    ],
);
Route::get(
    '/test',
    function () {
        return "test api";
    },
);
