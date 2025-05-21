<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\social\post\OccasionFactory;

class Occasion extends Model
{
    use HasFactory;
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


    protected static function newFactory()
    {
        return OccasionFactory::new();
    }
}
