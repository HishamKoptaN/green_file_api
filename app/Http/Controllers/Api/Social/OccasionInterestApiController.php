<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Social\Post\Occasion;

class OccasionInterestApiController extends Controller
{
    public function toggleInterest(Request $request, Occasion $occasion)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير مسجل الدخول'], 401);
        }

        if ($user->isInterestedIn($occasion)) {
            $user->interestedOccasions()->detach($occasion->id);
            return successRes(
                null,
                200,
            );
        } else {
            $user->interestedOccasions()->syncWithoutDetaching([$occasion->id]);
            return successRes(
                null,
                201,
            );
        }
    }
}
