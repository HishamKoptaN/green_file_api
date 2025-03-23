<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;

class CheckController extends Controller
{
    public function check()
    {
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
            return successRes([
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
