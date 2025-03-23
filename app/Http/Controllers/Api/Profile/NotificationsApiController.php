<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notification\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationsAppController extends Controller
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

    public function get(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $userId = $user->id;
        $notifications = Notification::where('public', true)
            ->orWhereHas(
                'users',
                function ($query) use ($user) {
                    $query->where(
                        'user_id',
                        $user->id,
                    );
                },
            )
            ->get();

        return successRes(
            $notifications,
        );
    }
    public function unreadNotifications()
    {
        return response()->json(Auth::user()->unreadNotifications);
    }
    public function allNotifications()
    {
        return response()->json(Auth::user()->notifications);
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'تم تعليم الإشعار كمقروء']);
    }

}
