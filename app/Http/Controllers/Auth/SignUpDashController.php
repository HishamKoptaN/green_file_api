<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Balance;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Hash;

class SignUpDashController extends Controller
{
    public function signUp(
        Request $request,
    ) {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email|max:255',
                    'password' => 'required|min:6',
                ],
            );
            if ($validator->fails()) {
                return failureResponse(
                    'Invalid input data.',
                    422
                );
            }
            $user = User::create(
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make(
                        $request->password,
                    ),
                    'account_number' => '24' . rand(
                        11111,
                        99999,
                    ),
                    'image' => "default.png",
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'balance' => 0,
                    'plan_id' => 1
                ],
            );
            if ($user) {
                Balance::create(
                    [
                        'user_id' => $user->id,
                        'available_balance' => 0,
                    ],
                );
                UserPlan::create(
                    [
                        'user_id' => $user->id,
                        'plan_id' => 1,
                    ],
                );
                $token = $user->createToken("auth", ['*'], now()->addWeek());
                $user->load(
                    [
                        'balance',
                        'userPlan.plan',
                    ],
                );
                return successResponse(
                    [
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ],
                    201
                );
            }
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }
}
