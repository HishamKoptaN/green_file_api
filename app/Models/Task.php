<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'name',
        'description',
        'amount',
        'link',
        'image',
    ];
    protected $casts = [
        'amount' => 'integer',
    ];
    public function getStatusAttribute($value)
    {
        return (bool) $value; // تحويل القيمة إلى boolean
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tasks');
    }
    public function taskProofs()
    {
        return $this->hasMany(TaskProof::class, 'task_id');
    }
}
