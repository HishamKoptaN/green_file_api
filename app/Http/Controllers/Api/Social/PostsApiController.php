<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Models\Social\Post\Post;

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
                    return $this->sharePost(
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
            $posts = Post::orderBy('created_at', 'desc')->with('user')->paginate(10);;
            return successRes(
                paginateRes(
                    $posts,
                    PostResource::class,
                    'posts',
                )
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
            $post = Post::create(
                [
                    'user_id' => auth()->id(),
                    'content' => $request->content,
                    'original_post_id' =>$request->original_post_id,
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
    public function destroy(
        $id,
    ) {
        $post = Post::find($id);
    }
}
