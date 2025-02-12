<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class NotificationUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'public',
        'message',
        'read_at',
    ];
    public function getPublicAttribute($value)
    {
        return (bool) $value;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id');
    }
}
