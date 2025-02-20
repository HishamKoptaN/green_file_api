<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\statuses\StatusFactory;
use App\Models\User\User;

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

    protected static function newFactory()
    {
        return StatusFactory::new();
    }
}
