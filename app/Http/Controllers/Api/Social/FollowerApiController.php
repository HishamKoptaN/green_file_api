<?php

namespace App\Http\Controllers\Api\Social;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\User\Company;

class FollowerApiController extends Controller
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
    public function follow(
        Request $request,
    ) {
        $user = auth()->user();
        $followableType = $request->input('followable_type');
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
    public function unfollow(Request $request)
    {
        $user = auth()->user();
        $followableType = $request->input('followable_type');
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
    public function following()
    {
        $user = auth()->user();
        return response()->json($user->following);
    }
}
