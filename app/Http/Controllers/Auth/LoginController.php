<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\Auth\InvalidIdToken;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Exception;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // البحث عن المستخدم في قاعدة البيانات
        $user = \App\Models\User::where('email', $request->email)->first();

        // التحقق من وجود المستخدم
        if (!$user) {
            return failureResponse('Email does not exist.');
        }

        // التحقق من صحة كلمة المرور
        if (!Hash::check($request->password, $user->password)) {
            return failureResponse('Invalid password.');
        }

        // التحقق من حالة المستخدم
        if (!$user->status) {
            return failureResponse(__('You have been blocked from the platform.'));
        }

        try {
            // إنشاء توكن باستخدام Sanctum
            $token = $user->createToken("auth", ['*'], now()->addWeek());

            // تحميل بيانات المستخدم المرتبطة
            $user->load(['balance', 'userPlan.plan']);
            $isVerified = !is_null($user->verified_at);

            // إرجاع استجابة تحتوي على التوكن وبيانات المستخدم
            return successResponse([
                'token' => $token->plainTextToken,
                'verified' => $isVerified,
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            // التعامل مع أي أخطاء قد تحدث أثناء توليد التوكن
            return failureResponse($th->getMessage());
        }
    }

}
