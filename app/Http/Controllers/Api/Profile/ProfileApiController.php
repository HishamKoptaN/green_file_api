<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Helpers\uploadImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\User\Company;
use App\Models\User\OpportunityLooking;
use App\Models\User\User;

class ProfileApiController extends Controller
{
    public function handleReq(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->edit(
                    $request,
                );
            case 'PATCH':
                return $this->updateUserProfile(
                    $request,
                );
            default:
                return failureRes(
                    'Invalid request method',
                );
        }
    }
    public function get(
        Request $request,
    ) {
        try {
            if ($request->has('user_id')) {
                $user = User::find($request->user_id);
                if (!$user) {
                    return failureRes(
                        'User not found',404
                   );
                }
                return successRes(
                    new ProfileResource(
                        $user,
                    ),
                );
            }
            if (!Auth::guard('sanctum')->check()) {
                return response()->json(
                    [
                        'error' => 'Unauthenticated',
                    ],
                    401
                );
            }
            $user = Auth::guard('sanctum')->user();
            return successRes(
                new ProfileResource(
                    $user,
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                 $e->getMessage(),
            );

        }
    }
    public function edit(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $userable = $user->userable;
            $newProfileImagePath = $userable->image;
            if ($request->hasFile('image')) {
                $newProfileImagePath = uploadImageHelper::updateImage(
                    $request,
                    $user,
                    'profile',
                    $userable->image,
                    'image',
                );
            }
            $newCoverImagePath = $userable->cover_image;
            if ($request->hasFile('cover_image')) {
                $newCoverImagePath = uploadImageHelper::updateImage(
                    $request,
                    $user,
                    'cover_image',
                    $userable->cover_image,
                    'cover_image',
                );
            }
            if ($userable instanceof Company) {
                $userable->update(
                    [
                        'name' => $request->name ?? $request->name,
                        'job_title' => $request->job_title ?? $userable->job_title,
                        'image' => $newImagePath ?? $userable->image,
                        'cover_image' => $newCoverImagePath ?? $userable->cover_image,
                        'address' => $request->price ?? $userable->address,
                    ],
                );
            } elseif ($userable instanceof OpportunityLooking) {
                if ($request->filled('name')) {
                    $nameParts = explode(' ', trim($request->name), 2);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? '';
                } else {
                    $firstName = $userable->first_name;
                    $lastName = $userable->last_name;
                }
                $userable->update(
                    [
                        'first_name' => $firstName ?? $userable->first_name,
                        'last_name' => $lastName ?? $userable->last_name,
                        'job_title' => $request->job_title ?? $userable->job_title,
                        'image' => $newProfileImagePath ?? $userable->image,
                        'cover_image' => $newCoverImagePath ?? $userable->cover_image,
                        'address' => $request->address ?? $userable->address,
                        'phone' => $request->phone ?? $userable->phone,
                    ],
                );
            }
            $userable->save();
            return successRes(
                new ProfileResource(
                    $user,
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                __('Failed to update user: ') . $e->getMessage(),
            );
        }
    }
}
