<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(
        Request $request,
    ) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return failureResponse(
                'Email does not exist.',
            );
        }
        if (!Hash::check($request->password, $user->password)) {
            return failureResponse(
                'Invalid password.',
            );
        }
        if (!$user->status) {
            return failureResponse(__('You have been blocked from the platform.'));
        }
        try {
            $token = $user->createToken("auth", ['*'], now()->addWeek());
            $user->load(
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
                    'token' => $token->plainTextToken,
                    'verified' => $isVerified,
                    'user' => $user
                ],
            );
        } catch (\Throwable $th) {
            return failureResponse(
                $th->getMessage(),
            );
        }
    }
}
