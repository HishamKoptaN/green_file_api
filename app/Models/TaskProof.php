<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class TaskProof extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'image',
        'task_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
    // تنسيق تواريخ الإنشاء والتحديث
    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }

    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d H:i'),
        );
    }

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المهمة
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
