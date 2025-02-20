<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\PostCmnt\PostCmntCollection;
use App\Http\Resources\PostCmnt\PostCmntResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Post\PostComment;

class PostCmntsApiController extends Controller
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

    public function get(
        $id,
    ) {
        try {
            if (!$id) {
                return response()->json(
                    [
                        'error' => 'Post ID is required',
                    ],
                    400,
                );
            }
            $cmnts = PostComment::where(
                'post_id',
                $id,
            )->paginate(20);
            return successRes(
                new PostCmntCollection($cmnts),

            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }

    public function create(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $comment = PostComment::create(
                [
                    'post_id' => $request->id,
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                ],
            );
            return successRes(
                new PostCmntResource(
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
