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
    public function post()
    {
        return $this->morphOne(
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
