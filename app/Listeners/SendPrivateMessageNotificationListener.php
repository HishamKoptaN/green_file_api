<?php

namespace App\Listeners;

use App\Events\PrivateMessageSent;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User\UserDeviceToken;
use App\Models\Notification\Notification as NotificationModel;
use App\Models\Notification\NotificationRecipient;

class SendPrivateMessageNotificationListener
{
    public function handle(PrivateMessageSent $event)
    {
        try {
            $messaging = (new Factory)
                ->withServiceAccount(base_path('storage/app/firebase/firebase_credentials.json'))
                ->createMessaging();
            $notification = Notification::create(
                $event->title,
                $event->body
            );
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData(array_merge(
                    ['click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'image' => $event->image],
                    $event->data
                ));

            $notificationModel = NotificationModel::create([
                'title' => $event->title,
                'body' => $event->body,
                'image' => $event->image,
                'data' => json_encode($event->data),
            ]);

            foreach ($event->userIds as $userId) {
                $tokens = UserDeviceToken::where('user_id', $userId)->pluck('device_token')->toArray();

                foreach ($tokens as $token) {
                    $messaging->send($message->withChangedTarget('token', $token));
                }

                NotificationRecipient::create([
                    'notification_id' => $notificationModel->id,
                    'user_id' => $userId,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }
    }
}
