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
        'type',
        'content',
        'image',
        'video',
        'original_post_id',
        'publish_at',
        'commentable_id',
        'commentable_type',
    ];
    public function scopePublished(
        $query,
    ) {
        return $query->whereNotNull(
            'publish_at',
        )
            ->where(
                'publish_at',
                '<=',
                now(),
            );
    }
    public function scopeNews(
        $query,
    ) {
        return $query->where(
            'type',
            'news',
        );
    }
    public function getIsFollowingAttribute()
    {
        $authUser = auth()->user();
        if (!$authUser) {
            return false;
        }
        return $authUser->following()->where('followed_id', $this->user_id)->exists();
    }
    public function scopeCompany(
        $query,
    ) {
        return $query->where(
            'type',
            'company',
        );
    }
    public function scopeRegular(
        $query,
    ) {
        return $query->where(
            'type',
            'social',
        );
    }
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
