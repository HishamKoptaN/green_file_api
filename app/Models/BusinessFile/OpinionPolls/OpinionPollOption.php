<?php

namespace App\Models\BusinessFile\OpinionPolls;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\BusinessFile\OpinionPolls\OpinionPollFactory;
use Illuminate\Database\Eloquent\Model;

class OpinionPollOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'opinion_poll_id',
    'option',
    'votes',
    ];
    public function opinionPoll()
    {
        return $this->belongsTo(OpinionPoll::class);
    }
    public function responses()
    {
        return $this->hasMany(
            OpinionPollResponse::class,
        );
    }
    protected static function newFactory()
    {
        return OpinionPollFactory::new();
    }
}
