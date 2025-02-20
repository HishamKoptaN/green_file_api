<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class friend extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
        );
    }

    public function friend()
    {
        return $this->belongsTo(
            User::class,
            'friend_id',
        );
    }
}
