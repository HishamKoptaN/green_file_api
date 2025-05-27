<?php

namespace App\Models\Global;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Report extends Model
{
    protected $fillable = ['user_id', 'reason'];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
