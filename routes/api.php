<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Profile\ProfileApiController;
use App\Http\Controllers\Api\Global\ReportApiController;
use App\Http\Controllers\Api\Global\HideApiController;
//! social
use App\Http\Controllers\Api\Social\LikeApiController;
use App\Http\Controllers\Api\Social\StatusesApiController;
use App\Http\Controllers\Api\Social\PostsApiController;
use App\Http\Controllers\Api\Social\CmntsApiController;
use App\Http\Controllers\Api\Social\FriendshipsApiController;
use App\Http\Controllers\Api\Social\FollowerApiController;
use App\Http\Controllers\Api\Social\OccasionInterestApiController;
use App\Http\Controllers\Api\Social\PollsApiController;
//! freelanceFile
use App\Http\Controllers\Api\FreelanceFile\Projects\ProjectsApiController;
use App\Http\Controllers\Api\JobsApiController;
//! BusinessFile
use App\Http\Controllers\Api\BusinessFile\NewsApiController;
use App\Http\Controllers\Api\BusinessFile\CompanyPostsApiController;
use App\Http\Controllers\Api\BusinessFile\OpinionPollsApiController;
use App\Http\Controllers\Api\BusinessFile\ServicesApiController;
use App\Http\Controllers\Api\BusinessFile\MissingServicesApiController;
use App\Http\Controllers\Api\BusinessFile\TrainingApiController;
use App\Http\Controllers\Api\Profile\NotificationsApiController;
use App\Http\Controllers\FirebaseNotificationController;
use App\Http\Controllers\Api\Cvs\CvsApiController;
use App\Http\Controllers\SpecializationsApiController;
use App\Http\Controllers\Api\Chats\ChatsApiController;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

Route::prefix('reports')->group(function () {
    Route::post('/', [ReportApiController::class, 'store']);
});
Route::prefix('hides')->group(function () {
    Route::post('/', [HideApiController::class, 'store']);
});
//! chats
Route::get(
    '/chats',
    [
        ChatsApiController::class,
        'get',
    ],
);
//! msgs
Route::prefix('msgs')->controller(ChatsApiController::class)->group(
    function () {
        Route::get(
            '{chat}',
            'getMsgs',
        );
        Route::post(
            'send',
            'send',
        );
    },
);
//! cv
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
//! notifications
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
//! social !//
//! statuses
Route::any(
    '/statuses',
    [
        StatusesApiController::class,
        'handleReq',
    ],
);
Route::get('/status/{status}/viewers', [StatusesApiController::class, 'viewers']);
Route::delete('/status/{status}', [StatusesApiController::class, 'destroy']);
Route::post('/status/{status}/msg', [StatusesApiController::class, 'msg']);
Route::post('/status/{status}/like', [StatusesApiController::class, 'toggleLike']);
Route::post('/status/{status}/report', [StatusesApiController::class, 'report']);
Route::post('/status/{status}/hide', [StatusesApiController::class, 'hide']);
Route::any( '/user-statuses', [StatusesApiController::class,'userStatuses',]);
Route::post('/status/{status}/view',[StatusesApiController::class, 'view']);
//! posts

Route::post(
    '/posts/draft',
    [
        PostsApiController::class,
        'createDraft',
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
//! delete post
Route::delete(
    'posts/{id}',
    [
        PostsApiController::class,
        'delete',
    ],
);
//! delete post
Route::post(
    '/votes',
    [
        PollsApiController::class,
        'vote',
    ],
);









//! posts
Route::post(
    'occasions/toggle-interest/{occasion}',
    [
        OccasionInterestApiController::class,
        'toggleInterest',
    ],
);


//! missing-services
Route::get(
    '/missing-services/{id?}',
    [
        MissingServicesApiController::class,
        'get',
    ],
);
Route::post(
    '/missing-services/{id?}',
    [
        MissingServicesApiController::class,
        'create',
    ],
);
//! likes
Route::any(
    '/likes/{id?}',
    [
        LikeApiController::class,
        'handleReq',
    ],
);
//! cmnts
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

Route::get(
    '/specializations',
    [
        SpecializationsApiController::class,
        'get',
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
        return "api route connected successfully";
    },
);
