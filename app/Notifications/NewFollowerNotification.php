<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewFollowerNotification extends Notification
{
    use Queueable;

    private $follower;

    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->follower->name} قام بمتابعتك!",
            'follower_id' => $this->follower->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "{$this->follower->name} قام بمتابعتك!",
            'follower_id' => $this->follower->id,
        ].
        ,
    );
    }
}
