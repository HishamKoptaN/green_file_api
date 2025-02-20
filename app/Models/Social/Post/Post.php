<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\PostFactory;
use App\Models\User\User;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'image_url',
        'video_url',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function comments()
    {
        return $this->hasMany(
            PostComment::class,
        );
    }
    public function likes()
    {
        return $this->hasMany(
            PostLike::class,
        );
    }
    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
