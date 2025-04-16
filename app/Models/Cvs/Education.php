<?php

namespace App\Models\Cvs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\cvs\EducationFactory;
use App\Models\Cvs\Cv;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'cv_id',
        'school',
        'start_date',
        'end_date',
        'education_level',
        'details',
        'grade',
        'institution_type',
        'city',
        'created_at',
        'updated_at',
    ];
    public function cv()
    {
        return $this->belongsTo(
            Cv::class,
        );
    }

    protected static function newFactory()
    {
        return EducationFactory::new();
    }
}
