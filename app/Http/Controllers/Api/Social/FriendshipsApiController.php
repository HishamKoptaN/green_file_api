<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\OpportunityLooking;
use App\Http\Resources\Social\FriendResource;

class FriendshipsApiController extends Controller
{
     //! عرض الاضدقاء
     public function friends()
     {
         $friends = auth()->user()->friends()->with(
             [
                 'user.userable',
                 'friend.userable',
             ],
         )->paginate(10);
         $friends->getCollection()->transform(
             function ($friendship) {
                 return $friendship->user_id == auth()->id() ? $friendship->friend : $friendship->user;
             }
         );
         return successRes(
             paginateRes(
                 $friends,
                 FriendResource::class,
                 'friends'
             )
         );
     }
    public function suggestedFriends()
    {
        $user = auth()->user();
        $myFriendsIds = $user->friends()
            ->pluck('friend_id')
            ->merge($user->friends()->pluck('user_id'))
            ->unique()
            ->filter(fn($id) => $id != $user->id)
            ->values();

        $friendsOfFriends = User::where('userable_type', OpportunityLooking::class)
            ->whereHas(
                'friends',
                function ($query) use ($myFriendsIds) {
                    $query->whereIn('user_id', $myFriendsIds)
                        ->orWhereIn('friend_id', $myFriendsIds);
                },
            )
            ->whereNotIn('id', $myFriendsIds)
            ->where('id', '!=', $user->id)
            ->limit(3)
            ->get();

        return successRes(
            FriendResource::collection(
                $friendsOfFriends,
            )
        );
    }
    //! إرسال طلب صداقة
    public function sendRequest(
        $friend_id,
    ) {
        $friend = User::findOrFail($friend_id);
        if (auth()->user()->id == $friend->id) {
            return failureRes();
        }
        if (auth()->user()->isFriendWith(
            $friend,
        )) {
            return failureRes(
                'هذا المستخدم صديقك بالفعل',
                400,
            );
        }
        auth()->user()->sendFriendRequest(
            $friend,
        );
        return successRes();
    }
    //! قبول طلب الصداقة
    public function acceptRequest(
        $friend_id,
    ) {
        $friend = User::findOrFail(
            $friend_id,
        );
        auth()->user()->acceptFriendRequest(
            $friend,
        );
        return successRes();
    }
    //! رفض طلب الصداقة
    public function rejectRequest(
        $friend_id,
    ) {
        $friend = User::findOrFail(
            $friend_id,
        );

        auth()->user()->rejectFriendRequest(
            $friend,
        );
        return successRes();
    }
    //! إلغاء الصداقة
    public function unfriend($friend_id)
    {
        $friend = User::findOrFail($friend_id);
        $friendship = auth()->user()->friends()
            ->where(
                function ($query) use ($friend) {
                    $query->where(
                        'friend_id',
                        $friend->id,
                    )
                        ->orWhere(
                            'user_id',
                            $friend->id,
                        );
                },
            )->first();

        if (!$friendship) {
            return failureRes(
                'هذا المستخدم ليس صديقك',
                400,
            );
        }
        $friendship->delete();
        return successRes();
    }

    //! عرض طلبات الصداقة الواردة
    public function incomingRequests()
    {
        $requests = auth()->user()->receivedFriendRequests()->with('user')->paginate(10);

        $requests->getCollection()->transform(
            fn($friendship) => $friendship->user
        );

        return successRes(
            paginateRes(
                $requests,
                FriendResource::class,
                'friends'
            )
        );
    }

    //! عرض طلبات الصداقة المرسلة
    public function outgoingRequests()
    {
        $requests = auth()->user()->sentFriendRequests()->with('friend')->paginate(10);
        $requests->getCollection()->transform(fn($f) => $f->friend);
        return successRes(
            paginateRes(
                $requests,
                FriendResource::class,
                'friends'
            )
        );
    }
}
