<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class StatusView extends Model
{
    protected $fillable = [
        'status_id',
        'user_id',
        'viewed_at',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
