<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'type',
        'amount',
        'message',
        'user_account_number',
        'method',
        'user_name',
        'image',
        'plan_id', 
        'user_id'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $appends = [
        'amount_formatted',
        'created_date',
    ];

    // appends
    public function amountFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->amount . getSetting('currency_symbol', "$"),
        );
    }

    public function createdDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->created_at->format('Y-m-d'),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
