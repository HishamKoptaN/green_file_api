<?php

namespace App\Models\Cvs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\cvs\CvFactory;
use App\Models\Cvs\WorkExperience;
use App\Models\Cvs\Education;
use App\Models\User\User;

class Cv extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_uid',
        'image',
        'first_name',
        'last_name',
        'job_title',
        'email',
        'phone',
        'city',
        'address',
        'birthdate',
        'marital_status',
        'gender',
        'nationality'
    ];
    public function workExperiences()
    {
        return $this->hasMany(
            WorkExperience::class,
        );
    }
    public function educations()
    {
        return $this->hasMany(
            Education::class,
        );
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    protected static function newFactory()
    {
        return CvFactory::new();
    }
}
