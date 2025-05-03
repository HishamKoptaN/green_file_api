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
        'publish_at',
        'postable_type',
        'postable_id',
    ];
    protected $with = ['postable'];

    //! Relations
    public function postable()
    {
        return $this->morphTo();
    }
    public function sharedPosts()
    {
        return $this->hasMany(SharedPost::class, 'post_id');
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function shares()
    {
        return $this->hasMany(SharedPost::class, 'post_id');
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
    //! end
    protected static function newFactory()
    {
        return PostFactory::new();
    }
    public function getStatusAttribute()
    {
        if (is_null($this->publish_at)) {
            return 'draft';
        }
        if ($this->publish_at > now()) {
            return 'scheduled';
        }
        return 'published';
    }

    public function sharedByUsers()
    {
        return $this->belongsToMany(User::class, 'post_shares')->withTimestamps();
    }
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

    public function getPostTypeAttribute()
    {
        return class_basename(
            $this->postable_type,
        );
    }
}
