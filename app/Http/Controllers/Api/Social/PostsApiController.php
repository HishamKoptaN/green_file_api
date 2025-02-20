<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Http\Resources\Social\Post\PostCollection;
use App\Models\Social\Post\Post;
use App\Models\Social\Post\PostLike;

class PostsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                if ($id) {
                    return $this->getCmnts(
                        $request,
                        $id,
                    );
                } else {
                    return $this->get(
                        $request,
                    );
                }

            case 'POST':
                if ($id) {
                    return $this->comment(
                        $request,
                        $id,
                    );
                } else {
                    return $this->create(
                        $request,
                    );
                }

            case 'PUT':
                return $this->toggleLike(
                    $id,
                );
            default:
                return $this->failureRes();
        }
    }
    public function get()
    {
        try {
            $posts = Post::with('user')->paginate(10);;
            return successRes(
                new PostCollection(
                    $posts,
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
            $post = Post::create(
                [
                    'user_id' => $user->id,
                    'content' => $request->content,
                ],
            );
            return successRes(
                new PostResource(
                    $post->fresh(),
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }

    public function toggleLike(
        $postId,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $post = Post::where('id', $postId)->first();

            if (!$post) {
                return failureRes("Post not found");
            }
            $like = PostLike::where('user_id', $user->id)->where('post_id', $postId)->first();
            if ($like) {
                $like->delete();
                return successRes();
            } else {
                PostLike::create(
                    [
                        'user_id' => $user->id,
                        'post_id' => $postId
                    ],
                );
                return successRes();
            }
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }
    public function destroy(
        $id,
    ) {
        $post = Post::find($id);
    }
}
