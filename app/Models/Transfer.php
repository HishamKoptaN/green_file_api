<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'amount',
        'net_amount',
        'rate',
        'message',
        'employee_id',
        'user_id',
        'sender_currency_id',
        'receiver_currency_id',
        'receiver_account',
        'image',
        'address',
        'created_at',
        'updated_at',
    ];
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
     public function senderCurrency()
    {
        return $this->belongsTo(Currency::class, 'sender_currency_id');
    }
    public function receiverCurrency()
    {
        return $this->belongsTo(Currency::class, 'receiver_currency_id');
    }
   
}