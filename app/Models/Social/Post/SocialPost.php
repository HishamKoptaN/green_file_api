<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\SocialPostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'image',
        'video',
        'thumbnail_url',
        'pdf',
    ];
    public function posts()
    {
        return $this->morphMany(
            Post::class,
            'postable',
        );
    }
    protected static function newFactory()
    {
        return SocialPostFactory::new();
    }
}
