<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'user_id',
        'amount',
        'comment',
        'method',
        'image',
        'created_at',
    ];
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format(
                'Y-m-d H:i',
            ),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format(
                'Y-m-d H:i',
            ),
        );
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function employee()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function currency()
    {
        return $this->belongsTo(
            Currency::class,
            'method',
            'id',
        );
    }
}
