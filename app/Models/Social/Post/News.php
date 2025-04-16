<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'content',
        'image',
        'video',
        'pdf',
    ];
    public function post()
    {
        return $this->morphOne(
            Post::class,
            'postable',
        );
    }
}
