<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use App\Models\User\UserDeviceToken;

class CheckController extends Controller
{
    public function check(
        Request $request,
    ) {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return response()->json(
                    [
                        'error' => 'Unauthenticated',
                    ],
                    401
                );
            }
            $user = Auth::guard('sanctum')->user();
            UserDeviceToken::updateOrCreate(
                [
                    'device_token' => $request->device_token,
                ],
                [
                    'user_id' => $user->id,
                    'device_type' => $request->device_type,
                    'updated_at' => now(),
                ],
            );
            return successRes(
                [
                    'role' => $user->getRoleNames()->first(),
                    'user' => new UserResource($user),
                ],
            );
        } catch (\Exception $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                500
            );
        }
    }
}
