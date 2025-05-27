<?php

namespace App\Models\Traits;
use App\Models\Social\Post\Post;

trait UserPostsTrait
{
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }
 public function sharedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_shares')->withTimestamps();
    }
}
