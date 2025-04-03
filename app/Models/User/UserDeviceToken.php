<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class UserDeviceToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
}
