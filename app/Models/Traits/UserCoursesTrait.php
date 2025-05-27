<?php

namespace App\Models\Traits;
use App\Models\Course\Course;
use App\Models\Course\CourseRating;
trait UserCoursesTrait
{
     // ! courses
     public function courses()
     {
         return $this->belongsToMany(Course::class);
     }
     public function courseRating()
     {
         return $this->hasMany(CourseRating::class);
     }


}
