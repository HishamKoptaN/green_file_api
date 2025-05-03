<?php

namespace App\Models\Chats;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_1_id',
        'user_2_id',
        'last_message',
    ];
    public function user1()
    {
        return $this->belongsTo(
            User::class,
            'user_1_id',
        );
    }
    public function user2()
    {
        return $this->belongsTo(
            User::class,
            'user_2_id',
        );
    }
    public function msgs()
    {
        return $this->hasMany(
            Msg::class,
        );
    }
    public function lastMsg()
    {
        return $this->hasOne(
            Msg::class,
        )->latestOfMany();
    }
}
