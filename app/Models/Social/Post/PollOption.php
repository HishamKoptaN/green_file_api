<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollOption extends Model
{
    use HasFactory;

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
    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }
}
