<?php

namespace App\Models\BusinessFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\Company;
use App\Models\Social\Post\Comment;

class CompanyPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'content',
    ];
    public function company()
    {
        return $this->belongsTo(
            Company::class,
        );
    }
    public function comments()
    {
        return $this->morphMany(
            Comment::class,
            'commentable',
        );
    }
}
