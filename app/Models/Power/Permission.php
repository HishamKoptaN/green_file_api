<?php

namespace App\Models\Power;

use Spatie\Permission\Models\Permission as SpatiePermission;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Permission extends Model
{
    protected $fillable = [
        'name',
    ];
}
