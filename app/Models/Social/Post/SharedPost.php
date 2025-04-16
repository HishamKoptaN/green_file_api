<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class SharedPost extends Model
{
    protected $fillable = [
        'post_id',
        'content',
    ];
    public function posts()
    {
        return $this->morphMany(
            Post::class,
            'postable',
        );
    }
}

