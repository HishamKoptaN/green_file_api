<?php

namespace App\Models\Social\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\post\PostFactory;
use App\Models\User\User;
class Scheduling extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'image',
        'video',
        'pdf',
    ];
}
