<?php

namespace App\Http\Controllers\Api\Chats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Chats\ChatResource;
use App\Http\Resources\Chats\MsgResource;
use App\Models\Chats\Chat;
use App\Events\PrivateMessageSent;

class ChatsApiController extends Controller
{
    public function get()
    {
        $userId = auth()->id();
        $chats = Chat::where('user_1_id', $userId)
            ->orWhere('user_2_id', $userId)
            ->with(
                [
                    'user1',
                    'user2',
                    'lastMsg.user.userable',
                ],
            )->withCount(
                [
                    'msgs as unread_count' => function ($query) use ($userId) {
                        $query->where('user_id', '!=', $userId)
                            ->whereNull('seen_at');
                    },
                ],
            )
            ->latest()
            ->paginate(10);

        return successRes(
            paginateRes(
                $chats,
                ChatResource::class,
                'chats'
            )
        );
    }
    public function getMsgs(
        Chat $chat,
    ) {
        $authId = auth()->id();
        // التحقق من صلاحية الوصول
        if ($chat->user_1_id !== $authId && $chat->user_2_id !== $authId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // تحميل الرسائل مع معلومات المستخدم المرتبطة
        $msgs = $chat->msgs()
            ->with('user.userable')
            ->latest()
            ->paginate(request('per_page', 20)); // دعم per_page بشكل اختياري

        return successRes(
            paginateRes(
                $msgs,
                MsgResource::class,
                'msgs'
            )
        );
    }

    public function createChat(
        $userId1,
        $userId2,
    ) {
        $chat = Chat::create(
            [
                'user_1_id' => $userId1,
                'user_2_id' => $userId2,
                'last_message' => '',
            ],
        );
        return response()->json($chat);
    }
    //! send
    public function send(
        Request $request,
    ) {
        $userId = auth()->id();
        $chat = Chat::find(
            $request->chat_id,
        );
        $msg = $chat->msgs()->create(
            [
                'user_id' => $userId,
                'msg' => $request->msg,
            ],
        );
        $chat->update(
            [
                'last_message' => $msg->msg
            ],
        );
        $otherUserId = $chat->user_one_id == $userId
            ? $chat->user_two_id
            : $chat->user_one_id;
        $isCurrentUser = $msg->user_id == auth()->id();
        $owner = optional(optional($msg->user)->userable);
        event(
            new PrivateMessageSent(
                userIds: [$userId, $otherUserId],
                title: 'رسالة',
                body: $msg->msg,
                image: '',
                data: [
                    'type' => 'msg',
                    'chat_id' =>  $chat->id,
                    'id' =>  $msg->id,
                    'msg' =>  $msg->msg,
                    'image' => $msg->image,
                    "video" => $msg->video,
                    'is_current_user' =>  $isCurrentUser,
                    'seen_at' =>  null,
                    'created_at' => $msg->created_at,
                ]
            ),
        );
        return successRes(
            new MsgResource(
                $msg->fresh(),
            ),
            201,
        );
    }
}
