<?php
namespace App\Http\Controllers;

use App\Models\User\User;
class FollowController extends Controller
{
    public function follow($id)
    {
        $user = auth()->user();
        $user->follow($id);
        return response()->json(['message' => 'تمت المتابعة']);
    }

    public function unfollow($id)
    {
        $user = auth()->user();
        $user->unfollow($id);
        return response()->json(['message' => 'تم إلغاء المتابعة']);
    }
    public function followers(User $user)
    {
        return response()->json($user->followers);
    }

    public function followings(User $user)
    {
        return response()->json($user->followings);
    }
}
