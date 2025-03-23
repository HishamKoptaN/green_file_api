<?php

namespace App\Models\BusinessFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\BusinessFile\NewsFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\Company;

class News extends Model
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
    protected static function newFactory()
    {
        return NewsFactory::new();
    }
}
