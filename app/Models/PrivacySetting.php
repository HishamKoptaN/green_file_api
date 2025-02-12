<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacySetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'post_visibility',
        'message_privacy',
        'friend_request_privacy',
        'show_last_seen',
        'show_phone_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
