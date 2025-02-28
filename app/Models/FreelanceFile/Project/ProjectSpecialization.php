<?php

namespace App\Models\FreelanceFile\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\PostFactory;
use App\Models\User\User;

class ProjectSpecialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function jobApplications()
    {
        return $this->hasMany(
            ProjectSpecialization::class,
            'job_id',
        );
    }
    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
