<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\user\CompanyFactory;
use App\Models\User\User;


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
    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
}
