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
        'image',
        'title',
        'description',
        'user_id',
        'specialization_id',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
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
