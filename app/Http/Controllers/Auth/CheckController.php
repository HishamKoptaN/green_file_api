<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckAuthController extends Controller
{
    public function check()
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return failureResponse(
                    [],
                    401,
                );
            }
            $user = Auth::guard('sanctum')->user()->load(
                [
                    'balance',
                    'userPlan.plan',
                ],
            );
            $isVerified = !is_null(
                $user->verified_at,
            );
            return successResponse(
                [
                    'verified' => $isVerified,
                    'user' => $user,
                ],
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }
}
