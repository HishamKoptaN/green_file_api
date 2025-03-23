<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\job\JobFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\Company;
use App\Models\Job\Skill;


class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function requiredSkills()
{
    return $this->belongsToMany(Skill::class, 'job_required_skills', 'job_id', 'skill_id');
}

    public function company()
    {
        return $this->belongsTo(
            Company::class,
        );
    }
    public function jobApplications()
    {
        return $this->hasMany(
            JobApplication::class,
            'job_id',
        );
    }
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'job_skills',
            'job_id',
            'skill_id',
        );
    }


    protected static function newFactory()
    {
        return JobFactory::new();
    }
}
