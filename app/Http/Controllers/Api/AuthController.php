<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function check(Request $request)
    {
        return response()->json([
            'message' => 'Authenticated',
            'user' => auth()->user(),
        ], 200);
    }
}
