<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rated_user_id' => 'required|exists:users,id',
            'rating_value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = Rating::create([
            'user_id' => auth()->id(), // الحصول على ID المستخدم المتصل
            'rated_user_id' => $request->rated_user_id,
            'rating_value' => $request->rating_value,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'تم إضافة التقييم بنجاح', 'rating' => $rating], 201);
    }

    // عرض تقييمات المستخدم
    public function show($userId)
    {
        $ratings = Rating::where('rated_user_id', $userId)->get();
        return response()->json($ratings);
    }
}
