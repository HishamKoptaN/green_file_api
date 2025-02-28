<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\User\User;

class FriendshipsController extends Controller
{
   //! إرسال طلب صداقة
   public function sendRequest($friend_id)
   {
       $friend = User::findOrFail($friend_id);

       if (auth()->user()->id == $friend->id) {
           return response()->json(['message' => 'لا يمكنك إضافة نفسك'], 400);
       }
       if (auth()->user()->isFriendWith($friend)) {
           return response()->json(['message' => 'هذا المستخدم صديقك بالفعل'], 400,
        );
       }
       auth()->user()->sendFriendRequest($friend);

       return response()->json(['message' => 'تم إرسال طلب الصداقة بنجاح']);
   }
   //! قبول طلب الصداقة
   public function acceptRequest($friend_id)
   {
       $friend = User::findOrFail($friend_id);

       auth()->user()->acceptFriendRequest($friend);

       return response()->json(['message' => 'تم قبول طلب الصداقة']);
   }
   //! رفض طلب الصداقة
   public function rejectRequest($friend_id)
   {
       $friend = User::findOrFail($friend_id);

       auth()->user()->rejectFriendRequest($friend);

       return response()->json(['message' => 'تم رفض طلب الصداقة']);
   }
   //! إلغاء الصداقة
   public function unfriend($friend_id)
   {
       $friend = User::findOrFail($friend_id);

       $friendship = auth()->user()->friends()
           ->where(function ($query) use ($friend) {
               $query->where('friend_id', $friend->id)
                     ->orWhere('user_id', $friend->id);
           })->first();

       if (!$friendship) {
           return response()->json(['message' => 'هذا المستخدم ليس صديقك'], 400);
       }

       $friendship->delete();

       return response()->json(['message' => 'تم إلغاء الصداقة']);
   }
   //! عرض أصدقائي
   public function listFriends()
   {
       $friends = auth()->user()->friends()->with('user', 'friend')->get();

       $friendList = $friends->map(function ($friendship) {
           if ($friendship->user_id == auth()->id()) {
               return $friendship->friend;
           } else {
               return $friendship->user;
           }
       });

       return response()->json(['friends' => $friendList]);
   }

   //! عرض طلبات الصداقة الواردة
   public function incomingRequests()
   {
       $requests = auth()->user()->receivedFriendRequests()->with('user')->get();

       $list = $requests->map(function ($friendship) {
           return $friendship->user;
       });

       return response()->json(['incoming_requests' => $list]);
   }
   //! عرض طلبات الصداقة المرسلة
   public function outgoingRequests()
   {
       $requests = auth()->user()->sentFriendRequests()->with('friend')->get();

       $list = $requests->map(function ($friendship) {
           return $friendship->friend;
       });

       return response()->json(['outgoing_requests' => $list]);
   }
}
