<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class CompanyPost extends Model
{
    protected $fillable = [
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
