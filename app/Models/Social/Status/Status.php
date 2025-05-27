<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\statuses\StatusFactory;
use App\Models\User\User;
use App\Models\Global\Report;
use App\Models\Global\Hide;
use App\Models\Social\Status\StatusMessage;

class Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'audio',
        'thumbnail_url',
        'font_family',
        'font_size',
        'font_color',
        'time',
    ];
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function hides()
    {
        return $this->morphMany(Hide::class, 'hideable');
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
        );
    }
    public function viewers()
    {
        return $this->belongsToMany(User::class, 'status_messages')
            ->withPivot('message')
            ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany(
            StatusView::class,
        );
    }

    public function likes()
    {
        return $this->hasMany(StatusLike::class);
    }
    public function statusMessages()
    {
        return $this->hasMany(StatusMessage::class);
    }
    protected static function newFactory()
    {
        return StatusFactory::new();
    }
}
