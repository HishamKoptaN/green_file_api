<?php

namespace App\Models;

use App\Casts\Serialize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content'
    ];

    protected $casts = [
        'content' => Serialize::class
    ];
}
