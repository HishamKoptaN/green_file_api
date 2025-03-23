<?php

namespace App\Models\FreelanceFile\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\freelanceFile\ProjectFactory;
use App\Models\User\User;
use App\Models\Job\Specialization;

class Project extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'image',
        'name',
        'description',
        'specialization_id',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',

        );
    }
    public function specialization()
    {
        return $this->hasOne(
            Specialization::class,
        );
    }
    protected static function newFactory()
    {
        return ProjectFactory::new();
    }
}
