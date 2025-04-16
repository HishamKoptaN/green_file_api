<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'image',
        'title',
        'description',
        'link',
        'start_at',
        'end_at',
    ];
    public function post()
    {
        return $this->morphOne(
            Post::class,
            'postable',
        );
    }
}
