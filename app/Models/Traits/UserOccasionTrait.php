<?php

namespace App\Models\Traits;
use App\Models\Social\Post\Occasion;

trait UserOccasionTrait
{
    public function interestedOccasions()
    {
        return $this->belongsToMany(
            Occasion::class,
            'occasion_user',
            'user_id',
            'occasion_id'
        );
    }
    public function isInterestedIn(Occasion $occasion): bool
    {
        return $this->interestedOccasions()
            ->where(
                'occasion_id',
                $occasion->id,
            )
            ->exists();
    }
}
