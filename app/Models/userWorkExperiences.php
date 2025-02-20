<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class userWorkExperiences extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'job_title', 'company_name', 'start_date', 'end_date', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
