<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent
{
    use Dispatchable, SerializesModels;
    public $userId;
    public $title;
    public $body;
    public $image;
    public $data;

    public function __construct(
        $userId,
        $title,
        $body,
        $image = null,
        $data = [],
    ) {
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
        $this->data = $data;
    }
}
