<?php

namespace App\Http\Controllers\Api;


use App\Models\Post;
use Illuminate\Http\Request;

class customApiController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->store(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function store(Request $request)
    {
        try {
            $post = Post::create(
                [
                    'user_id' => auth()->id(),
                    'content' => $request->content,
                ],
            );
            return $this->successRes(
                $post,
            );
        } catch (\Exception $e) {
            return $this->failureRes(
                $e->getMessage(),
            );
        }
    }

    public function update(
        Request $request,
        $id,
    ) {
        $post = Post::find($id);
        $post->update(
            [
                'status' => $request->input('status'),
                'name' => $request->input('name'),
                'link' => $request->input('link'),
                'description' => $request->input('description'),
                'amount' => $request->input('amount'),
            ],
        );
    }
    public function destroy(
        $id,
    ) {
        $post = Post::find($id);
    }
}
