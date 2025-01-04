<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassController extends Controller
{
    public function edit(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'current_password' => 'required',
                    'new_password' => 'required|string|min:4|max:200|confirmed',
                ]
            );

            if ($validator->fails()) {
                return failureResponse(
                    $validator->errors()->first(),
                    422
                );
            }
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return failureResponse(
                    __('User not authenticated.'),
                    401
                );
            }
            if (!Hash::check($request->current_password, $user->password)) {
                return failureResponse(
                    __('Current password is incorrect.'),
                    400
                );
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            return successResponse();
        } catch (\Exception $e) {
            return failureResponse(
                __('Failed to update password: ') . $e->getMessage(),
            );
        }
    }
}
