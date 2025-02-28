<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Social\Post\Like;

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
        $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string|in:post,product'
        ]);
        $modelMapping = [
            'post' => 'App\\Models\\Post',
            'product' => 'App\\Models\\Product',
        ];

        $likeableType = $modelMapping[$request->likeable_type] ?? null;

        if (!$likeableType) {
            return response()->json(['error' => 'Invalid likeable_type'], 400);
        }

        $user = Auth::user();
        $existingLike = Like::where([
            'user_id' => $user->id,
            'likeable_id' => $request->likeable_id,
            'likeable_type' => $likeableType,
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json();
        } else {
            Like::create([
                'user_id' => $user->id,
                'likeable_id' => $request->likeable_id,
                'likeable_type' => $likeableType,
            ]);
            return response()->json();
        }
    }
}
