<?php

namespace App\Models\Social\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\social\statuses\StatusFactory;
use App\Models\User\User;
use App\Models\Social\Status\Status;


class StatusLike extends Model
{
    // اسم جدول قاعدة البيانات (لو لم يكن الاسم 'status_likes' افتراضي)
    protected $table = 'status_likes';

    // الحقول التي يمكن تعبئتها بشكل جماعي (Mass Assignment)
    protected $fillable = [
        'user_id',
        'status_id',
    ];

    // علاقات الموديل (لو حبيت تربط المستخدم والحالة)

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الحالة (Status)
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
