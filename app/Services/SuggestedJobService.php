<?php

namespace App\Services;

use App\Models\Job\Job;

class SuggestedJobService
{
    public static function getFor($user, $limit = 2)
    {
        $skills = $user->skills?->pluck('id')->toArray() ?? [];

        $query = Job::with('company');
        if (!empty($skills)) {
            $query->whereHas(
                'requiredSkills',
                function ($q) use ($skills) {
                    $q->whereIn('skills.id', $skills);
                },
            );
        }

        return $query->latest()->take($limit)->get();
    }
}
