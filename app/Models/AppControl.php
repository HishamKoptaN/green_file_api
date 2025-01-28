<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppControl extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_name',
        'message',
        'status',
    ];
    public function getStatusAttribute(
        $value,
    ) {
        return (bool) $value;
    }
}
