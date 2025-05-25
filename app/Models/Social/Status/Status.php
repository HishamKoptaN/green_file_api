<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\statuses\StatusFactory;
use App\Models\User\User;

class Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'audio',
        'font_family',
        'font_size',
        'font_color',
        'time',
    ];
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function views()
    {
        return $this->hasMany(
            StatusView::class,
        );
    }
    public function viewers()
    {
        return $this->hasMany(StatusView::class, 'status_id');
    }
    public function likes()
    {
        return $this->hasMany(StatusLike::class);
    }

    public function comments()
    {
        return $this->hasMany(StatusMessage::class);
    }

    protected static function newFactory()
    {
        return StatusFactory::new();
    }
}
