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
    // public function createAccount(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     try {
    //         // إنشاء حساب مستخدم جديد باستخدام Firebase
    //         $firebaseUser = $this->firebaseAuth->createUser([
    //             'email' => $request->input('email'),
    //             'password' => $request->input('password'),
    //             'emailVerified' => false,
    //         ]);

    //         // إضافة المستخدم إلى قاعدة بيانات Laravel
    //         $user = User::create([
    //             'email' => $request->input('email'),
    //             'name' => $request->input('name', 'Guest'),
    //             'firebase_uid' => $firebaseUser->uid,
    //         ]);

    //         return response()->json([
    //             'message' => 'User created successfully',
    //             'user' => $user,
    //         ], 201);
    //     } catch (AuthException $e) {
    //         Log::error('Firebase Authentication error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Authentication error'], 500);
    //     } catch (\Exception $e) {
    //         Log::error('Unexpected error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Something went wrong'], 500);
    //     }
    // }
}
