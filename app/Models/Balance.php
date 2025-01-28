<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Balance extends Model
{
    protected $fillable = [
        'user_id',
        'available_balance',
        'suspended_balance'
    ];
    protected $appends = [
        'total_balance',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function getTotalBalanceAttribute()
    {
        return $this->available_balance + $this->suspended_balance;
    }
}
