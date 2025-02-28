<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CustomFactory;
use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    use HasFactory;
    protected $fillable = [
    ];


 protected static function newFactory()
 {
     return CustomFactory::new();
 }
}
