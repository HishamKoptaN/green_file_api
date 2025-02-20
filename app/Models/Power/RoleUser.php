<?php

namespace App\Models\Power;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = 'role_users';
    protected $fillable = [
        'role_id',
        'user_id',
    ];

    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
        );
    }
}
