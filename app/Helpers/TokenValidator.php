<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\Auth\InvalidIdToken;
use Illuminate\Support\Facades\Log;
use App\Helpers\TokenValidator;

class FirebaseAuthController extends Controller
{
    protected $tokenValidator;
    protected $firebaseAuth;

    public function __construct()
    {
        $this->tokenValidator = new TokenValidator();

        // تهيئة Firebase Auth
        $path = base_path('storage/firebase/credentials.json');
        $this->firebaseAuth = (new \Kreait\Firebase\Factory)
            ->withServiceAccount($path)
            ->createAuth();
    }

    /**
     * تسجيل الدخول باستخدام Google ID Token
     */
    public function google(Request $request)
    {
        try {
            $idToken = $request->bearerToken();

            if (!$idToken) {
                return response()->json(['error' => 'Token is missing'], 400);
            }

            // التحقق من التوكن باستخدام Firebase
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);
            $claims = $verifiedIdToken->claims();
            $uid = $claims->get('sub');
            $email = $claims->get('email');
            $name = $claims->get('name', 'Guest');

            // إنشاء أو جلب المستخدم من قاعدة البيانات
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'verified_at' => now(),
                ]
            );

            // إنشاء توكن Sanctum للمستخدم
            $token = $user->createToken('Google Login')->plainTextToken;

            return response()->json([
                'token' => $token,
                'verified' => !is_null($user->verified_at),
                'user' => $user,
            ], 200);

        } catch (InvalidIdToken $e) {
            return response()->json(['error' => 'Invalid Firebase ID Token'], 400);
        } catch (AuthException $e) {
            return response()->json(['error' => 'Firebase Authentication error'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * فحص المستخدم المصادق عليه باستخدام Sanctum
     */
    public function checkAuthenticatedUser()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['user' => $user], 200);
    }

    /**
     * إنشاء حساب جديد باستخدام Firebase
     */
    public function createAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // إنشاء حساب مستخدم جديد باستخدام Firebase
            $user = $this->firebaseAuth->createUser([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'emailVerified' => false, // لا تحقق من البريد الإلكتروني بعد
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);

        } catch (AuthException $e) {
            Log::error('Firebase Authentication error: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication error'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
