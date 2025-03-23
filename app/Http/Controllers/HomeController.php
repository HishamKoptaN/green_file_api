<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Social\FriendshipsApiController;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $onlineFriends = app(FriendshipsApiController::class)->onlineFriends()->getData(true);
        $suggestedFriends = app(FriendshipsApiController::class)->suggestedFriends()->getData(true);
        // $suggestedPages = app(PageController::class)->suggestedPages()->getData(true);
        return response()->json(
            [
                'online_friends' => $onlineFriends,
                'suggested_friends' => $suggestedFriends,
                // 'suggested_pages' => $suggestedPages,
            ],
        );
    }
}
