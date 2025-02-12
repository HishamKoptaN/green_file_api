<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = [
        'name',
    ];
    public function getStatusAttribute(
        $value,
    ) {
        return (bool) $value;
    }
    public function country()
    {
        return $this->belongsTo(
            Country::class,
        );
    }
}
