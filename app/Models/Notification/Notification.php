<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\User\User;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'image',
        'title',
        'body',
        'public',
        'message',
        'data',
        'is_read',
    ];
    public function recipients()
    {
        return $this->hasMany(
            NotificationRecipient::class,
        );
    }
    public function getPublicAttribute(
        $value,
    ) {
        return (bool) $value;
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'notification_user'
        );
    }
    public function notifiable()
    {
        return $this->morphTo();
    }
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
}
