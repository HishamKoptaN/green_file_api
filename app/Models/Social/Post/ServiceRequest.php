<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'specialization_id',
        'details',
    ];
    public function posts()
    {
        return $this->morphMany(
            Post::class,
            'postable',
        );
    }
}
