<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class PostComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];
    public function post()
    {
        return $this->belongsTo(
            Post::class,
        );
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    protected static function newFactory()
    {
        return \Database\Factories\Social\Post\PostCommentFactory::new();
    }
}
