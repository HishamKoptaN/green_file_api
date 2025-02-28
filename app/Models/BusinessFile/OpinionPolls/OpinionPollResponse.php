<?php

namespace App\Models\BusinessFile\OpinionPolls;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\BusinessFile\OpinionPolls\OpinionPollResponseFactory;
use Illuminate\Database\Eloquent\Model;

class OpinionPollResponse extends Model
{
    use HasFactory;
    protected $fillable = [
        'survey_option_id',
        'user_id',
    ];
    public function option()
    {
        return $this->belongsTo(
            OpinionPollOption::class,
        );
    }
    protected static function newFactory()
    {
        return OpinionPollResponseFactory::new();
    }
}
