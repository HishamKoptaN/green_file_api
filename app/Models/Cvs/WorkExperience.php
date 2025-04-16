<?php

namespace App\Models\Cvs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\cvs\WorkExperienceFactory;


class WorkExperience extends Model
{
    use HasFactory;

    protected $table = 'work_experiences';

    protected $fillable = [
        'cv_id',
        'position',
        'job_title',
        'company_name',
        'start_date',
        'end_date',
        'address',
        'details',
    ];

    public function cv()
    {
        return $this->belongsTo(
            Cv::class,
        );
    }

    protected static function newFactory()
    {
        return WorkExperienceFactory::new();
    }
}
