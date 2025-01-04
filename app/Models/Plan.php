<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'transfer_commission',
        'user_amount_per_referal',
        'refered_amount',
        'amount_after_count',
        'count',
        'Transfer commission',
        'discount',
        'discount_type',
        'daily_transfer_count',
        'monthly_transfer_count',
        'max_transfer_count',
    ];

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }
    public function findOnlineEmployee()
    {
        return DB::table('user_has_roles')
            ->join('users', 'user_has_roles.user_id', '=', 'users.id')
            ->where('user_has_roles.role_id', 3)
            ->where('users.online_offline', 'online')
            ->select('users.*')
            ->first();
    }
}
