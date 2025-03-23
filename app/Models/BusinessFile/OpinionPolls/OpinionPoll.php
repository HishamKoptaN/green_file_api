<?php

namespace App\Models\BusinessFile\OpinionPolls;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\BusinessFile\OpinionPolls\OpinionPollFactory;
use App\Models\User\Company;

class OpinionPoll extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'company_id',
        'content',
        'end_date',
    ];
    public function responses()
    {
        return $this->hasMany(
            OpinionPollResponse::class,
            'opinion_poll_id',
        );
    }

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_id',
        );
    }
    public function options()
    {
        return $this->hasMany(
            OpinionPollOption::class,
        );
    }
    protected static function newFactory()
    {
        return OpinionPollFactory::new();
    }
}
