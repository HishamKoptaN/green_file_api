<?php

namespace App\Models\Social\Post;
use Illuminate\Database\Eloquent\Model;
class Occasion extends Model
{
    protected $fillable = [
        'image',
        'title',
        'description',
        'link',
        'start_at',
        'end_at',
    ];
    public function posts()
    {
        return $this->morphMany(
            Post::class,
            'postable',
        );
    }
    public function interestedUsers()
    {
        return $this->belongsToMany(
            \App\Models\User\User::class,
            'occasion_user',
            'occasion_id',
            'user_id'
        );
    }

    public function getInterestedCountAttribute()
    {
        return $this->interestedUsers()->count();
    }
}
