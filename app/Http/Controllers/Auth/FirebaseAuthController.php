<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\Auth\InvalidIdToken;
use Illuminate\Support\Facades\Hash;
use App\Models\Balance;
use App\Models\UserPlan;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;
    public function __construct()
    {
        $credentialsPath = base_path('storage/firebase/credentials.json');
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Firebase credentials file is missing.');
        }
        $this->firebaseAuth = (new \Kreait\Firebase\Factory)
            ->withServiceAccount($credentialsPath)
            ->createAuth();
    }

    public function google(
        Request $request,
    ) {
        $idToken = $request->bearerToken();
        if (!$idToken) {
            return response()->json(
                [
                    'error' => 'id_token is required',
                ],
                400,
            );
        }
        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken(
                $idToken,
            );
            $email = $verifiedIdToken->claims()->get('email');
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $fullName = $verifiedIdToken->claims()->get('name');
            $nameParts = explode(
                ' ',
                $fullName,
            );
            $firstName = $nameParts[0] ?? 'Guest';
            $lastName = $nameParts[1] ?? '';
            $image = $verifiedIdToken->claims()->get('picture');
            $user = User::firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'email' => $email,
                    'firebase_uid' => $firebaseUid,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make(
                        "password",
                    ),
                    'account_number' => '24' . rand(
                        11111,
                        99999,
                    ),
                    'image' =>  $image,
                    'verified_at' => now(),
                ]
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
                $token = $user->createToken('Google Login', ['*'], now()->addWeek())->plainTextToken;
                $user->load(
                    [
                        'balance',
                        'userPlan.plan',
                    ],
                );
                return successResponse(
                    [
                        'token' => $token,
                        'verified' => is_null(
                            $user->verified_at,
                        ),
                        'user' => $user,
                    ],
                    201
                );
            }
        } catch (InvalidIdToken $e) {
            return failureResponse(
                'Invalid ID token.',
                401,
            );
        } catch (AuthException $e) {
            return failureResponse(
                'Authentication error: ' . $e->getMessage(),
                401,
            );
        } catch (\Exception $e) {
            return failureResponse(
                'An error occurred: ' . $e->getMessage(),
            );
        }
    }
}
