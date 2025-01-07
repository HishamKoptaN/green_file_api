<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->token()->revoke();
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'User is not logged in',
        ], 401);
    }
}
