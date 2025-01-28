<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'currency_id',
        'account_number',
        'comment',
    ];

    public function currency()
    {
        return $this->belongsTo(
            Currency::class,
        );
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
}
