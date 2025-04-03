<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Notification\Notification;

class NotificationRecipient extends Model
{
    use HasFactory;
    protected $table = 'notification_recipients';
    protected $fillable = [
        'notification_id',
        'user_id',
        'public',
        'message',
        'is_read',
        'read_at',
    ];
    protected $casts = [
        'public' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
