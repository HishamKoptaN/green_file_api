<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;

class FollowerController extends Controller
{
    public function follow(
        Request $request,
    ) {
        $user = auth()->user();
        $followableType = $request->input('followable_type'); // "user" أو "company"
        $followableId = $request->input('followable_id');

        if ($followableType === 'user') {
            $followable = User::findOrFail($followableId);
        } elseif ($followableType === 'company') {
            $followable = Company::findOrFail($followableId);
        } else {
            return response()->json(['error' => 'Invalid followable type'], 400);
        }

        $user->following()->attach($followable);

        return response()->json(['message' => 'Followed successfully']);
    }

    /**
     * إلغاء متابعة مستخدم أو شركة.
     */
    public function unfollow(Request $request)
    {
        $user = auth()->user();
        $followableType = $request->input('followable_type'); // "user" أو "company"
        $followableId = $request->input('followable_id');

        if ($followableType === 'user') {
            $followable = User::findOrFail($followableId);
        } elseif ($followableType === 'company') {
            $followable = Company::findOrFail($followableId);
        } else {
            return response()->json(['error' => 'Invalid followable type'], 400);
        }

        $user->following()->detach($followable);

        return response()->json(['message' => 'Unfollowed successfully']);
    }

    /**
     * عرض قائمة المتابعين لمستخدم أو شركة.
     */
    public function followers($id, $type)
    {
        if ($type === 'user') {
            $followable = User::findOrFail($id);
        } elseif ($type === 'company') {
            $followable = Company::findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json($followable->followers);
    }

    /**
     * عرض قائمة الحسابات التي يتابعها المستخدم.
     */
    public function following()
    {
        $user = auth()->user();
        return response()->json($user->following);
    }
}
