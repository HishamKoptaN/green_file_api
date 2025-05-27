<?php

namespace App\Models\Global;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Hide extends Model
{
    protected $fillable = ['user_id',];
    public function hideable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
