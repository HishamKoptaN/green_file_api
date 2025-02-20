<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\PostFactory;
use App\Models\User\User;
use App\Models\Job\Specialization;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_title',
        'description',
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
        return PostFactory::new();
    }
}
