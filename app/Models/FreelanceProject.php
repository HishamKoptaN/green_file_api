<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
class FreelanceProject extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'title', 'description', 'budget_min', 'budget_max', 'duration_days'];

    public function client()
    {
        return $this->belongsTo(User::class);
    }
}
