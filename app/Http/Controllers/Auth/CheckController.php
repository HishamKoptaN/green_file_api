<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    public function check()
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return response()->json(
                    ['error' => 'Unauthenticated'],
                    401
                );
            }
            $user = Auth::guard('sanctum')->user();
            return response()->json(
                ['data' => $user],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                500
            );
        }
    }
}
