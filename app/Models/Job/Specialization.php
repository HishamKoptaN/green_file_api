<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\job\SpecializationFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    protected static function newFactory()
    {
        return SpecializationFactory::new();
    }
}
