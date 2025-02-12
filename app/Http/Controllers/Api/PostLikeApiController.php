<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\PostLike;

class PostLikeController extends Controller
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
    public function toggleLike($postId)
    {
        $like = PostLike::where('post_id', $postId)->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'تم إلغاء الإعجاب']);
        } else {
            PostLike::create(['post_id' => $postId, 'user_id' => auth()->id()]);
            return response()->json(['message' => 'تم الإعجاب بالمنشور']);
        }
    }

    public function index() {}


    public function create() {}


    public function store(Request $request) {}


    public function show(PostLike $like) {}


    public function edit(PostLike $like) {}


    public function update(Request $request, PostLike $like) {}


    public function destroy(PostLike $like) {}
}
