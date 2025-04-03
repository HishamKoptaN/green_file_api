<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserDeviceToken;

class UserDeviceTokenApiController extends Controller
{
    public function storeOrUpdateDeviceToken(
        Request $request,
    ) {
        $user = Auth::user();
        $existingToken = UserDeviceToken::where('user_id', $user->id)
            ->where('device_token', $request->device_token)
            ->first();

        if (!$existingToken) {
            // إذا لم يكن موجودًا، قم بإضافته
            UserDeviceToken::create([
                'user_id' => $user->id,
                'device_token' => $request->device_token,
                'device_type' => $request->device_type,
            ]);
        }
        return response()->json([
            'message' => 'Device token processed successfully',
        ],
    );
    }
}
