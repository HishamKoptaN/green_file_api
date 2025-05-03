<?php

namespace App\Models\BusinessFile;

use Database\Factories\BusinessFile\MissingServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class MissingService extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'specialization_id',
        'details',
    ];
    public function user()
    {
        return $this->belongsTo(
            Service::class,
        );
    }
    // protected static function newFactory()
    //  {
    //      return MissingServiceFactory::new();
    //  }
}
