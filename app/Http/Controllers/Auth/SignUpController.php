<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\User\OpportunityLooking;
use App\Models\User\User;
use App\Models\User\Company;
class SignUpController extends Controller
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
    public function countries()
    {
        try {
            $countries = Country::all();
            $cities = City::all();
            return successRes(
                [
                    "countries" => $countries,
                    "cities" => $cities,
                ]
            );
        } catch (\Exception $e) {
            return failureRes(
                [
                    $e->getMessage(),
                    500
                ]
            );
        }
    }
    public function jobSeekerSignUp(
        Request $request,
    ) {
        try {
            $id_token = $request->input('id_token');
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken(
                $id_token,
            );
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $user = User::create(
                [
                    'firebase_uid' => $firebaseUid,
                ],
            );
            $user->assignRole('opportunity_looking');
            $opportunityLooking = OpportunityLooking::create(
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'image' => "default.png",
                ],
            );
            // PrivacySetting::create([
            //     'user_id' => $user->id,
            //     'post_visibility' => 'friends',
            //     'message_privacy' => 'everyone',
            //     'friend_request_privacy' => 'everyone',
            //     'show_last_seen' => true,
            //     'show_phone_number' => false,
            // ]);
            $user->tokens()->create(
                [
                    'name' => 'auth-token',
                    'token' => hash('sha256', $id_token),
                ],
            );

            return successRes(
                $opportunityLooking,
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function companySignUp(
        Request $request,
    ) {
        try {
            $id_token = $request->input('id_token');
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken(
                $id_token,
            );
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $user = User::create(
                [
                    'firebase_uid' => $firebaseUid,
                ],
            );
            $company = Company::create(
                [
                    'user_id' => $user->id,
                    'firebase_uid' => $firebaseUid,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'image' => "default.png",

                ],
            );
            $user->assignRole('company');
            return successRes(
                $company,
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function assignRoleToUser()
    {

        // العثور على المستخدم بناءً على الـ ID
        $user = User::find(1);

        if (!$user) {
            return "المستخدم غير موجود.";
        }

        // تعيين الدور للمستخدم
        $user->assignRole('opportunity_looking');

        return "تم تعيين الدور  للمستخدم بنجاح.";
    }
}
