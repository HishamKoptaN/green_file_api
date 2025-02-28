<?php

namespace App\Models\BusinessFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\BusinessFile\TrainingFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\Company;

class Training extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'image',
        'title',
        'description',
    ];
    public function company()
    {
        return $this->belongsTo(
            Company::class,
        );
    }
    protected static function newFactory()
    {
        return TrainingFactory::new();
    }
}
