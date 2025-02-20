<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class course extends Model
{
    use HasFactory;
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(CourseRating::class);
    }
}
