<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'image_url',
        'video_url',
        'time',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
}
