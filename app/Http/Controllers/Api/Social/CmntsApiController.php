<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Cmnt\CmntResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Post\Comment;
use App\Models\Social\Post\Post;
use App\Events\NotificationSent;

class CmntsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {

            case 'GET':
                return $this->get(
                    $id,
                );
            case 'POST':
                return $this->create(
                    $request,
                );
            default:
                return response()->json(
                    [
                        'error' => 'Method Not Allowed',
                    ],
                    405,
                );
        }
    }

    public function get(
        $id,
    ) {
        try {
            if (!$id) {
                return response()->json([
                    'error' => 'Post ID is required',
                ], 400);
            }
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'error' => 'Post not found',
                ], 404);
            }
            $comments = Comment::where(
                'commentable_id',
                $id,
            )
                ->where(
                    'commentable_type',
                    Post::class,
                )
                ->latest()
                ->paginate(20);
            return successRes(
                paginateRes(
                    $comments,
                    CmntResource::class,
                    'cmnts',
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }

    public function create(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // جلب اسم المستخدم
            $userName = $user->userable ? $user->userable->first_name : $user->first_name;

            // تحديد نوع النموذج القابل للتعليق عليه
            $modelMapping = [
                'post' => Post::class,
            ];
            $commentableType = $modelMapping[$request->commentable_type] ?? null;

            if (!$commentableType) {
                return response()->json(['error' => 'Invalid commentable type'], 400);
            }
            // جلب المنشور لمعرفة المالك
            $post = Post::find($request->commentable_id);
            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            // إنشاء التعليق
            $comment = Comment::create([
                'comment' => $request->comment,
                'user_id' => Auth::id(),
                'commentable_id' => $request->commentable_id,
                'commentable_type' => $commentableType,
            ]);
            // إرسال إشعار لصاحب المنشور
            event(
                new NotificationSent(
                    userId: $post->user_id,
                    title: 'تعليق جديد',
                    body: "{$userName} قام بالتعليق على منشورك!",
                    image: $user->userable->image,
                    data: [
                        'key' => 'value',
                    ]
                ),
            );
            return successRes(new CmntResource($comment->fresh()), 201);
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }
}
