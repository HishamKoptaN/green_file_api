<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'status',
        'question',
        'end_date',
    ];
    public function posts()
    {
        return $this->morphMany(
            Post::class,
            'postable',
        );
    }
    public function options()
    {
        return $this->hasMany(
            PollOption::class,
        );
    }
}
