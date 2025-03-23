<?php

namespace App\Http\Controllers\Api\Social;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Cmnt\CmntResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Post\Comment;
use App\Models\Social\Post\Post;

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
                return response()->json(['error' => 'Method Not Allowed'], 405);
        }
    }

    public function get($id)
    {
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
            $comments = Comment::where('commentable_id', $id)
                ->where('commentable_type', Post::class)
                ->latest()
                ->paginate(20);

            return successRes(paginateRes($comments, CmntResource::class, 'cmnts'));
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }

    public function create(Request $request,
    )
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $modelMapping = [
                'post' => Post::class,
                'post' => Post::class,
            ];
            $commentableType = $modelMapping[$request->commentable_type] ?? null;

            $comment = Comment::create([
                'comment' => $request->comment,
                'user_id' => Auth::id(),
                'commentable_id' => $request->commentable_id,
                'commentable_type' => $commentableType,
            ]);
            return successRes(
                new CmntResource(
                    $comment->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
}
