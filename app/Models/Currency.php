<?php

namespace App\Models;

use App\Casts\Serialize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'name',
        'name_code',
        'selling_rate',
        'buying_rate',
        'payment_info',
        'comment',
        'created_at',
        'updated_at',
    ];
    public function getStatusAttribute($value)
    {
        return (bool) $value;
    }
    protected $casts =  [
        'payment_info' => Serialize::class,
        'updated_at' => 'datetime',
    ];
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => __($value),
        );
    }
    public function rates()
    {
        return $this->hasMany(Rate::class, 'currency_id');
    }
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
}
