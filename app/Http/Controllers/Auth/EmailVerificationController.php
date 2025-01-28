<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendOtp;
use App\Models\Otp;

class EmailVerificationController extends Controller
{
    public function sendEmailOtp(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return failureResponse('User not authenticated.', 401);
            }

            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(5);

            Otp::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'otp' => $otp,
                    'expires_at' => $expiresAt,
                ]
            );

            $user->notify(new SendOtp($otp));

            return successResponse();
        } catch (\Exception $e) {
            return failureResponse(
                __('Failed to send OTP: ') . $e->getMessage(),
                500
            );
        }
    }

    public function verifyEmailOtp(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return failureResponse('User not authenticated.', 401);
        }
        $otp = Otp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->first();

        if (!$otp) {
            return failureResponse('Invalid OTP.', 400);
        }

        if ($otp->expires_at < now()) {
            return failureResponse('Expired OTP.', 400);
        }

        $user->update(['verified_at' => now()]);

        return successResponse('Email verified successfully.');
    }
}
