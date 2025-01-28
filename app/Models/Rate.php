<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'plan_id',
        'from',
        'to',
        'price',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'price' => 'integer',
    ];
    public function getStatusAttribute($value)
    {
        return (bool) $value; // تحويل القيمة إلى boolean
    }
    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public static function groupByPlanId()
    {
        return self::all()->groupBy('plan_id');
    }

    public function getFromCurrencyNameAttribute()
    {
        return $this->fromCurrency ? $this->fromCurrency->name : null;
    }
    public function getToCurrencyNameAttribute()
    {
        return $this->toCurrency ? $this->toCurrency->name : null;
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
    public static function getToBinanceRates($planId)
    {
        return self::where([
            ['plan_id', $planId],
            ['to', 1],
            ['from', 2]
        ])->get()->map(
            function ($rate) {
                return [
                    'price' => $rate->price,
                    'updated_at' => $rate->updated_at,
                    'currency_name' => $rate->fromCurrency->name,
                    'from' => $rate->fromCurrency->id,
                ];
            },
        );
    }
}
