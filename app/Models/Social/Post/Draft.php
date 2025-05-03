<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'pdf',
    ];
    public function posts()
{
    return $this->morphMany(Post::class, 'postable');
}
}
