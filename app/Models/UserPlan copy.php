<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'upgraded_at',
    ];
    protected $dates = [];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    // public function plan()
    // {
    //     return $this->belongsTo(
    //         Plan::class,
    //     );
    // }
}
