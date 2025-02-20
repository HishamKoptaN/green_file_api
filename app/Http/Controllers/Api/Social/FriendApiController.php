<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User\User;

class FriendController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    // public function sendRequest($friend_id)
    // {
    //     $user = auth()->user();

    //     if (Friend::where('user_id', $user->id)->where('friend_id', $friend_id)->exists()) {
    //         return response()->json(['message' => 'تم إرسال الطلب بالفعل'], 400);
    //     }

    //     Friend::create(
    //         [
    //             'user_id' => $user->id,
    //             'friend_id' => $friend_id,
    //             'status' => 'pending',
    //         ],
    //     );

    //     return response()->json(['message' => 'تم إرسال طلب الصداقة']);
    // }

    // public function acceptRequest($friend_id)
    // {
    //     $user = auth()->user();

    //     $friendRequest = Friend::where('friend_id', $user->id)
    //         ->where('user_id', $friend_id)
    //         ->where('status', 'pending')
    //         ->first();

    //     if (!$friendRequest) {
    //         return response()->json(['message' => 'لا يوجد طلب صداقة'], 404);
    //     }

    //     $friendRequest->update(['status' => 'accepted']);

    //     return response()->json(['message' => 'تم قبول طلب الصداقة']);
    // }

    // public function declineRequest($friend_id)
    // {
    //     $user = auth()->user();

    //     $friendRequest = Friend::where('friend_id', $user->id)
    //         ->where('user_id', $friend_id)
    //         ->where('status', 'pending')
    //         ->first();

    //     if (!$friendRequest) {
    //         return response()->json(['message' => 'لا يوجد طلب صداقة'], 404);
    //     }

    //     $friendRequest->delete();

    //     return response()->json(['message' => 'تم رفض طلب الصداقة']);
    // }

    // public function listFriends()
    // {
    //     $user = auth()->user();

    //     $friends = Friend::where(function ($query) use ($user) {
    //         $query->where('user_id', $user->id)
    //             ->orWhere('friend_id', $user->id);
    //     })->where('status', 'accepted')->get();

    //     return response()->json($friends);
    // }
}
