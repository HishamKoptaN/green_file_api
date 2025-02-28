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
        'original_post_id',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function comments()
    {
        return $this->morphMany(
            Comment::class,
            'commentable',
        );
    }
    public function likes()
    {
        return $this->morphMany(
            Like::class,
            'likeable',
        );
    }
    public function originalPost()
    {
        return $this->belongsTo(
            Post::class,
            'original_post_id',
        );
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
