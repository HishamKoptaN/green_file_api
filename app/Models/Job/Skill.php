<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\job\SkillFactory;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    protected static function newFactory()
    {
        return SkillFactory::new();
    }
}
