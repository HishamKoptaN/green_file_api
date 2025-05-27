<?php

namespace App\Models\Traits;
use App\Models\Social\Status\Status;
use App\Models\Social\Status\StatusLike;
use App\Models\Social\Status\StatusMessage;
use App\Models\Social\Status\StatusView;
trait UserStatusTrait
{
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
    public function viewedStatuses()
    {
        return $this->belongsToMany(Status::class, 'status_messages')
            ->withPivot('message')
            ->withTimestamps();
    }
    public function likedStatuses()
    {
        return $this->hasMany(StatusLike::class);
    }
    public function statusComments()
    {
        return $this->hasMany(StatusMessage::class);
    }
    public function statusViews()
    {
        return $this->hasMany(StatusView::class);
    }

}
