<?php

namespace App\Http\Controllers\Api\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotificationResource;
use App\Models\Notification\Notification as NotificationModel;
use App\Models\Notification\NotificationRecipient;
use App\Events\NotificationSent;

class NotificationsApiController extends Controller
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
                return $this->create(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function create(
        Request $request,
    ) {
        try {
            event(
                new NotificationSent(
                    userId: $request->user_id,
                    title: 'عنوان الإشعار',
                    body: $request->body ?? 'أُعجب شخص ما بمنشورك!',
                    image: $request->image ?? null,
                    data: [
                        'key' => 'value',
                    ]
                ),
            );
            return successRes();
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }
    public function get(
        Request $request,
    ) {
        $user = Auth::guard('sanctum')->user();
        $userId = $user->id;
        //! جلب الإشعارات
        $notifications = NotificationModel::where(
            'type',
            'global',
        )
            ->orWhereHas(
                'recipients',
                function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
            )
            ->latest()
            ->paginate(
                10,
            );
        //! حساب عدد الإشعارات غير المقروءة
        $unreadCount = NotificationModel::whereHas(
            'recipients',
            function ($query) use (
                $userId,
            ) {
                $query->where(
                    'user_id',
                    $userId,
                )
                    ->where(
                        'is_read',
                        0,
                    );
            },
        )->count();
        return successRes(
            [
                'notifications' => paginateRes(
                    $notifications,
                    NotificationResource::class,
                    'notifications',
                ),
                'unread_count' => $unreadCount,
                //! إضافة عدد الإشعارات غير المقروءة في الاستجابة
            ],
        );
    }

    public function unreadNotifications()
    {
        return response()->json(
            Auth::user()->unreadNotifications,
        );
    }
    public function allNotifications()
    {
        return response()->json(
            Auth::user()->notifications,
        );
    }
    public function markAsRead($notificationId)
    {
        $user = Auth::guard('sanctum')->user();
        $notificationRecipient = NotificationRecipient::where('user_id', $user->id)
            ->where('notification_id', $notificationId)
            ->first();

        if ($notificationRecipient) {
            $notificationRecipient->is_read = 1;
            $notificationRecipient->save();
            return successRes(['message' => 'Notification marked as read']);
        }
        return failureRes('Notification not found or already read', 404);
    }
}
