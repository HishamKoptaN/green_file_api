<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Notifications\SendOtp;
use App\Models\Otp;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return failureRes('User not found.', 404);
            }
            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(5);
            // تحديث أو إنشاء OTP للمستخدم
            Otp::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'otp' => $otp,
                    'expires_at' => $expiresAt,
                ]
            );
            $user->notify(
                new SendOtp(
                    $otp,
                ),
            );
            return successRes();
        } catch (\Throwable $th) {
            return failureRes(
                $th->getMessage(),
                500,
            );
        }
    }
    public function verifyOtp(
        Request $request,
    ) {
        $user = User::where(
            'email',
            $request->email,
        )->first();
        $otp = Otp::where(
            'user_id',
            $user->id,
        )->where(
            'otp',
            $request->otp,
        )->first();
        if (!$otp || $otp->expires_at < now()) {
            return failureRes(
                'Invalid OTP.',
            );
        } else if ($otp->expires_at < now()) {
            return failureRes(
                'expired OTP.',
            );
        } else {
            return successRes();
        }
    }
    public function resetPassword(
        Request $request,
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'otp' => 'required|string',
                'new_password' => 'required|string|min:6|max:200|confirmed',
            ],
        );
        if ($validator->fails()) {
            return failureRes(
                $validator->errors()->first(),
                422,
            );
        }
        $otp = Otp::where(
            'otp',
            $request->otp,
        )->first();
        if (!$otp) {
            return failureRes(
                'Invalid OTP.',
            );
        }
        if ($otp->expires_at < now()) {
            return failureRes(
                'Expired OTP.',
            );
        }
        $user = User::where(
            'id',
            $otp->user_id,
        )->first();
        if (!$user) {
            return failureRes(
                'User not found.',
                404,
            );
        }
        $user->password = Hash::make(
            $request->new_password,
        );
        $user->save();
        $user->tokens()->delete();
        $user->load(
            [
                'balance',
                'userPlan',
            ],
        );
        $token = $user->createToken("auth", ['*'], now()->addWeek());
        return successRes(
            [
                'token' => $token->plainTextToken,
                'user' => $user
            ],
        );
    }
}
