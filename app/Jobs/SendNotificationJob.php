<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
    public function handle()
    {

    }
}
