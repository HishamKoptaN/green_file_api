<?php

namespace App\Http\Controllers\Api\Cvs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\uploadImageHelper;
use App\Models\Cvs\Cv;
use App\Models\Cvs\WorkExperience;
use App\Models\Cvs\Education;
use Illuminate\Support\Facades\DB;

class CvsApiController extends Controller
{
    public function getCv()
    {
        $user = Auth::user();
        $cv = Cv::with(
            'workExperiences',
            'educations',
        )
            ->where(
                'user_id',
                $user->id,
            )
            ->first();
        if (!$cv) {
            $cv = Cv::create(
                [
                    'user_id' => $user->id,
                    'image' => env('APP_URL') . '/public/media/profile/cv/default.png',
                    'first_name' => $user->first_name ?? '',
                    'last_name' => $user->last_name ?? '',
                    'job_title' => '',
                    'email' => $user->email ?? null,
                    'phone' => $user->phone ?? null,
                ],
            );
            $cv->workExperiences()->create([]);
            $cv->educations()->create([]);
        }

        return successRes(
            [
                'id' => $cv->id,
                'user_id' => $cv->user_id,
                'user_uid' => $cv->user_uid,
                'image' => $cv->image,
                'first_name' => $cv->first_name,
                'last_name' => $cv->last_name,
                'job_title' => $cv->job_title,
                'email' => $cv->email,
                'phone' => $cv->phone,
                'city' => $cv->city,
                'address' => $cv->address,
                'birthdate' => $cv->birthdate,
                'marital_status' => $cv->marital_status,
                'gender' => $cv->gender,
                'nationality' => $cv->nationality,
                'work_experience' => $cv->workExperiences->first(),
                'education' => $cv->educations->first(),
            ],
        );
    }
    public function updateCv(
        Request $request,
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'first_name'       => 'nullable|string',
                'last_name'        => 'nullable|string',
                'job_title'        => 'nullable|string',
                'email'            => 'nullable|email',
                'phone'            => 'nullable|string',
                'city'             => 'nullable|string',
                'address'          => 'nullable|string',
                'birthdate'        => 'nullable|date',
                'marital_status'   => 'nullable|string',
                'gender'           => 'nullable|string',
                'nationality'      => 'nullable|string',
                'work_experience'  => 'nullable|json',
                'education'        => 'nullable|json',
            ],
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = Auth::user();
        $cv = Cv::where('user_id', $user->id)->first();
        if (!$cv) {
            return response()->json([
                'message' => 'CV not found for the authenticated user',
            ], 404);
        }
        if ($request->hasFile('image')) {
            $newCvImagePath = uploadImageHelper::updateImage(
                $request,
                $user,
                'cvs',
                $cv->image,
                'image'
            );
            $request->merge(
                [
                    'image' => $newCvImagePath,
                ],
            );
        }

        $cv->update(
            $request->only(
                [
                    'user_uid',
                    'image',
                    'first_name',
                    'last_name',
                    'job_title',
                    'email',
                    'phone',
                    'city',
                    'address',
                    'birthdate',
                    'marital_status',
                    'gender',
                    'nationality'
                ],
            ),
        );

        // تحديث أو إنشاء work experience
        if ($request->filled('work_experience')) {
            $data = json_decode($request->work_experience, true);

            if (is_array($data)) {
                $workExperience = $cv->workExperiences()->first();

                if ($workExperience) {
                    $workExperience->update($data);
                } else {
                    $cv->workExperiences()->create($data);
                }
            }
        }
        // تحديث أو إنشاء education
        if ($request->filled('education')) {
            $data = json_decode($request->education, true);

            if (is_array($data)) {
                $education = $cv->educations()->first();

                if ($education) {
                    $education->update($data);
                } else {
                    $cv->educations()->create($data);
                }
            }
        }

        $cv = $cv->load(
            'workExperiences',
            'educations',
        );

        return response()->json(
            [
                'message' => 'CV updated successfully!',
                'cv' => $cv,
            ],
        );
    }
}
