<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\job\JobSkillFactory;

class JobSkill extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'skill_id',
    ];
    protected static function newFactory()
    {
        return JobSkillFactory::new();
    }
}
