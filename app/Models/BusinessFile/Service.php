<?php

namespace App\Models\BusinessFile;
use Database\Factories\BusinessFile\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'name',
        'description',
        'price',
        'image',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,
    );
    }
    protected static function newFactory()
     {
         return ServiceFactory::new();
     }
}
