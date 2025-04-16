<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = [
        'poll_id',
        'option',
        'votes',
    ];
    public function poll()
    {
        return $this->belongsTo(
            Poll::class,
        );
    }
}
