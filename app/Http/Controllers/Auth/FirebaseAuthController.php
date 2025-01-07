<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\Auth\InvalidIdToken;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function google(Request $request)
    {
        // التحقق من وجود id_token في الطلب
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // التحقق من توكن جوجل
            $idToken = $request->input('id_token');
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);

            // الحصول على بيانات المستخدم من جوجل
            $claims = $verifiedIdToken->claims();
            $uid = $claims->get('sub'); // معرف المستخدم
            $email = $claims->get('email'); // البريد الإلكتروني
            $name = $claims->get('name', 'Guest'); // الاسم الافتراضي إذا لم يكن موجودًا

            // البحث عن المستخدم أو إنشائه إذا لم يكن موجودًا
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'verified' => true]
            );

            // التحقق من تفعيل الحساب
            if (!$user->verified) {
                return response()->json(['message' => 'Account not verified'], 403);
            }
            // إنشاء توكن للمستخدم باستخدام Sanctum
            $token = $user->createToken('Google Login')->plainTextToken;
            // إرجاع استجابة تحتوي على التوكن وبيانات المستخدم
            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (InvalidIdToken $e) {
            // سجل الخطأ وأعد استجابة مفصلة
            Log::error('Invalid Google ID Token: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid Google ID Token'], 400);
        } catch (AuthException $e) {
            Log::error('Firebase Authentication error: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication error'], 500);
        } catch (Exception $e) {
            Log::error('Unexpected error in Google login: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
