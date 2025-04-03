<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User\UserDeviceToken;
use App\Models\Notification\Notification as NotificationModel;
use App\Models\Notification\NotificationRecipient;

class SendNotificationListener
{
    public function __construct()
    {
        //!
    }
    public function handle(
        NotificationSent $event,
    ) {
        //! جلب جميع التوكنات للمستخدم
        $tokens = UserDeviceToken::where('user_id', $event->userId)
            ->pluck('device_token')
            ->toArray();

        try {
            //! تهيئة Firebase
            $messaging = (new Factory)
                ->withServiceAccount(base_path('storage/app/firebase/firebase_credentials.json'))
                ->createMessaging();
            //! إنشاء الإشعار بدون صورة مباشرة
            $notification = Notification::create(
                $event->title,
                $event->body,
            );
            //! إنشاء الرسالة مع البيانات
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData(
                    array_merge(
                        [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'image' => $event->image,
                        ],
                        $event->data
                    )
                );
            //! إرسال الإشعار لكل توكن
            foreach ($tokens as $token) {
                $messaging->send(
                    $message->withChangedTarget('token', $token)
                );
            }
            //! حفظ بيانات الإشعار في قاعدة البيانات
            $notificationModel = NotificationModel::create(
                [
                    'title' => $event->title ?? '',
                    'body' => $event->body,
                    'image' => $event->image,
                    'data' => json_encode($event->data),
                ],
            );
            NotificationRecipient::create(
                [
                    'notification_id' => $notificationModel->id,
                    'user_id' => $event->userId,
                ],
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }
    }
}
