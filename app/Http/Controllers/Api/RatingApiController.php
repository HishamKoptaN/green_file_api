<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
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
    public function store(Request $request)
    {
        $request->validate([
            'rated_user_id' => 'required|exists:users,id',
            'rating_value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ],
    );
        $rating = Rating::create([
            'user_id' => auth()->id(),
            'rated_user_id' => $request->rated_user_id,
            'rating_value' => $request->rating_value,
            'comment' => $request->comment,
        ],
    );
        return response()->json(['message' => 'تم إضافة التقييم بنجاح', 'rating' => $rating], 201);
    }

    public function show($userId)
    {
        $ratings = Rating::where('rated_user_id', $userId)->get();
        return response()->json($ratings);
    }
}
