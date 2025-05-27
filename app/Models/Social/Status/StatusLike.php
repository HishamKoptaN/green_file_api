<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\statuses\StatusFactory;
use App\Models\User\User;
use App\Models\Social\Status\Status;
class StatusLike extends Model
{
    protected $table = 'status_likes';
    protected $fillable = [
        'user_id',
        'status_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
