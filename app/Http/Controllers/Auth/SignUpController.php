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
use App\Http\Resources\Auth\SignUpResource;

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
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {

            case 'GET':
                return $this->get(
                    $id,
                );
            case 'POST':
                return $this->signUp(
                    $request,
                );
            default:
                return response()->json(['error' => 'Method Not Allowed'], 405);
        }
    }
    public function get()
    {
        try {
            $countries = Country::all();
            $cities = City::all();
            $opportunityLookings = OpportunityLooking::all();
            return successRes(
                [
                    "countries" => $countries,
                    "cities" => $cities,
                    "opportunity_lookings" => SignUpResource::collection(
                        $opportunityLookings,
                    ),
                ],
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
    public function signUp(Request $request)
    {
        try {
            $id_token = $request->input('id_token');
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($id_token);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            if ($request->userable_type === 'company') {
                return $this->companySignUp($request, $firebaseUid);
            } elseif ($request->userable_type === 'opportunity_looking') {
                return $this->jobSeekerSignUp($request, $firebaseUid);
            }
            return failureRes("نوع المستخدم غير صالح.");
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }
    public function jobSeekerSignUp(
        Request $request,
        $firebaseUid,

    ) {
        try {
            $opportunityLooking = OpportunityLooking::create(
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'image' => "default.png",
                ],
            );
            $user = User::create(
                [
                    'firebase_uid' => $firebaseUid,
                    'userable_id' => $opportunityLooking->id,
                    'userable_type' => OpportunityLooking::class,
                ],
            );
            $user->assignRole('opportunity_looking');
            $token = $user->createToken("auth", ['*'], now()->addWeek())->plainTextToken;
            return successRes(
                [
                    'token' => $token,
                    'role' => $user->getRoleNames()->first(),
                    'user' =>  $opportunityLooking,
                ],
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function companySignUp(
        Request $request,
        $firebaseUid,
    ) {
        try {
            $company = Company::create(
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'image' => "default.png",

                ],
            );
            $user = User::create(
                [
                    'firebase_uid' => $firebaseUid,
                    'userable_id' => $company->id,
                    'userable_type' => Company::class,
                ],
            );
            $user->assignRole('company');
            $token = $user->createToken("auth", ['*'], now()->addWeek())->plainTextToken;
            return successRes(
                [
                    'token' => $token,
                    'role' => $user->getRoleNames()->first(),
                    'user' =>  $company,
                ],
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
}
