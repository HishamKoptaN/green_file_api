<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_uid',
        'name',
        'image',
        'phone',
        'country_id',
        'city_id',
    ];
    public function user()
    {
        return $this->morphOne(
            User::class,
            'userable',
        );
    }
    public function followers()
    {
        return $this->morphToMany(
            User::class,
            'followable',
            'followers',
            'followable_id',
            'user_id',
        )
            ->withTimestamps();
    }
}
