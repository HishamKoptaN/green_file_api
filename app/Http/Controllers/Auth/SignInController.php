<?php

namespace App\Http\Controllers\Auth;

use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;
use App\Http\Resources\User\UserResource;

class LoginController extends Controller
{
    protected $firebaseAuth;
    public function __construct()
    {
        $credentialsPath = base_path('storage/app/firebase/firebase_credentials.json');
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Firebase credentials file is missing.');
        }
        $this->firebaseAuth = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->createAuth();
    }
    public function authToken(
        Request $request,
    ) {
        $id_token = $request->input('id_token');
        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken(
                $id_token,
            );
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $user = User::where('firebase_uid', $firebaseUid)->first();
            $token = $user->createToken("auth", ['*'], now()->addWeek())->plainTextToken;
            return successRes(
                [
                    'token' => $token,
                    'role' => $user->getRoleNames()->first(),
                    'user' => new UserResource(
                        $user,
                    ),
                ],
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
                401,
            );
        }
    }

    public function check(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return failureRes(['message' => 'المستخدم غير موجود', 'code' => 404]);
            }
            return successRes(['user' => $user]);
        } catch (\Exception $e) {
            return failureRes(['message' => 'التوكن غير صالح', 'code' => 401]);
        }
    }
}
