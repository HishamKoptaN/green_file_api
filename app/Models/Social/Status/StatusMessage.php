<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class StatusMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status_id',
        'message',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function status()
    {
        return $this->belongsTo(
            Status::class,
        );
    }
}
