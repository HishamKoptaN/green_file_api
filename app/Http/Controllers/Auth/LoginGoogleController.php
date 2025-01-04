<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginGoogleController extends Controller
{
    public function completeGoogleLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|max:100|confirmed',
                'code' => 'nullable|string',
            ],
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $validator->errors()->first()
                ],
            );
        }

        $request->user()->update(
            [
                'password' => Hash::make($request->password),
                'code' => $request->code
            ],
        );

        return [
            'status' => true,
        ];
    }
    public function googleLogin(Request $request)
    {
        $create_password = false;
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'name' => 'required|string'
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->first()
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $create_password = true;
            $user = User::create(
                [
                    'status' => "active",
                    'token' => str()->random(),
                    'name' => $request->name,
                    'first_name' => $request->name . '-' . str()->random(3),
                    'last_name' => $request->name . '-' . str()->random(3),
                    'password' => Hash::make(str()->random()),
                    'email' => $request->email,
                    'image' => "default.png",
                    'address' => null,
                    'phone' => null,
                    'inactivate_end_at' => null,
                    'message' => null,
                ],
            );

            $currencies = Currency::get();
            $account_info = [];

            foreach ($currencies as $currency) {
                $account_info[] = [
                    'currency' => $currency->name,
                    'value' => "",
                ];
            }

            $user->account_info = $account_info;
            $user->save();

            $user->markEmailAsVerified();

            $user->assignRole('Member');
        }

        try {
            if ($user) {
                $token = $user->createToken("auth", ['*'], now()->addWeek());

                return response()->json([
                    'status' => true,
                    'create_password' => $create_password,
                    'token' => $token->plainTextToken
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'error' => $th->getMessage(),
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => __('Error try again later.'),
        ]);
    }
}
