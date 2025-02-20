<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\job\JobApplicationFactory;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'message',
        'job_id',
        'user_id',
    ];
    protected static function newFactory()
    {
        return JobApplicationFactory::new();
    }
}
