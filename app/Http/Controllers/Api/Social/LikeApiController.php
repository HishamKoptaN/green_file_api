<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Social\Post\Like;
use App\Events\NotificationSent;

class LikeApiController extends Controller
{
    public function handleReq(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->toggleLike(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function toggleLike(Request $request)
    {
        $modelMapping = [
            'post' => 'App\\Models\\Social\\Post\\Post',
        ];

        $likeableType = $modelMapping[$request->likeable_type] ?? null;
        $user = Auth::user();
        $likeableItem = $likeableType::find(
            $request->likeable_id,
        );
        if (!$likeableItem) {
            return response()->json(
                [
                    'error' => 'Item not found',
                ],
                404,
            );
        }
        //! التحقق مما إذا كان الإعجاب موجودًا بالفعل
        $existingLike = Like::where(
            [
                'user_id' => $user->id,
                'likeable_id' => $request->likeable_id,
                'likeable_type' => $likeableType,
            ],
        )->first();
        if ($existingLike) {
            $existingLike->delete();
            return response()->json(
                [
                    'message' => 'Like removed',
                ],
            );
        } else {
            Like::create(
                [
                    'user_id' => $user->id,
                    'likeable_id' => $request->likeable_id,
                    'likeable_type' => $likeableType,
                ],
            );
            //! التحقق من وجود المالك
            if (isset($likeableItem->user) && $likeableItem->user->id !== $user->id) {
                //! الحصول على اسم المستخدم بشكل ديناميكي
                $userName = $user->userable ? $user->userable->first_name : $user->first_name;
                $image = $user->userable ? $user->userable->image : null;
                event(
                    new NotificationSent(
                        userId: $likeableItem->user->id,
                        title: 'إشعار جديد',
                        body: "{$userName} أُعجب بمنشورك!",
                        image: $image,
                        data: [
                            'key' => 'value',
                        ]
                    ),
                );
            }
            return response()->json(
                [],
            );
        }
    }
}
