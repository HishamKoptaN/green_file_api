<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\CommentFactory;
use App\Models\User\User;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'comment',
        'commentable_id',
        'commentable_type',
    ];
    public function commentable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}
