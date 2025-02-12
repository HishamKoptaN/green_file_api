<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityLooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'status',
        'first_name',
        'last_name',
        'current_job',
        'account_number',
        'image',
        'address',
        'country_id',
        'city_id',
        'area_id',
        'birth_date',
        'marital_status',
        'gender',
        'nationality',
        'job_title',
        'phone',
        'phone_verified_at',
        'inactivate_end_at',
        'comment',
        'country_code',
        'verified_at',
    ];
    public function user()
    {
        return $this->morphOne(
            User::class,
            'userable',
        );
    }
}
