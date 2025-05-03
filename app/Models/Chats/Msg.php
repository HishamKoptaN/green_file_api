<?php

namespace App\Models\Chats;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Msg extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'user_id',
        'msg',
        'image',
        'video',
        'seen_at',
    ];
    public function chat()
    {
        return $this->belongsTo(
            Chat::class,
        );
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
}
