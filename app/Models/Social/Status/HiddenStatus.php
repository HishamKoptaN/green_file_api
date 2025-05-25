<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class HiddenStatus extends Model
{

    protected $fillable = ['user_id', 'status_id'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
