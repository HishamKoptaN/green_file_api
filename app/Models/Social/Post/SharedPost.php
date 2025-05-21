<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SharedPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'content',
    ];
    public function postable()
    {
        return $this->morphOne(Post::class, 'postable');
    }
    // العلاقة التي تربط هذا المنشور بالمنشورات المرتبطة به
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }
    // العلاقة التي تربط المنشور الأصلي بالـ SharedPost
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
