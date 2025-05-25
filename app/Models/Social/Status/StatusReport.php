<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Model;

class StatusReport extends Model
{
    protected $fillable = ['user_id', 'status_id', 'reason'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class);
    }
}
