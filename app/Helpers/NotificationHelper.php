<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Events\NewNotificationEvent;

class NotificationHelper
{
    public static function sendPublicNotification($message)
    {
        // إرسال الإشعار عبر Pusher
        event(new NewNotificationEvent($message));

        // حفظ الإشعار في الكاش
        $notifications = Cache::get('public_notifications', []);
        $notifications[] = ['message' => $message, 'time' => now()];
        Cache::put('public_notifications', $notifications, now()->addMinutes(10));

        Log::info("📢 إشعار جديد: " . $message);
    }
}
