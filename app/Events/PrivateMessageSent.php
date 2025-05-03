<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateMessageSent
{
    use Dispatchable, SerializesModels;
    public $userIds;
    public $title;
    public $body;
    public $image;
    public $data;

    public function __construct(
        $userIds,
        $title,
        $body,
        $image = null,
        $data = [],
    ) {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
        $this->data = $data;
    }
}
