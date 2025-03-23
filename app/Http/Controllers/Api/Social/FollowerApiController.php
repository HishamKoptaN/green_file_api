<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Social\FollowerResource;
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;
use App\Models\User\Company;

class FollowerApiController extends Controller
{
    public function followers(Request $request)
    {
        $user = $this->getUserFromRequest(
            $request,
        );
        $followers = $user->followers()->paginate(10);
        return successRes(
            paginateRes(
                $followers,
                FollowerResource::class,
                'followers'
            )
        );
    }

    public function followings(
        Request $request,
    ) {
        $user = $this->getUserFromRequest(
            $request,
        );

        $followings = $user->followings()->paginate(10);
        return successRes(
            paginateRes(
                $followings,
                FollowerResource::class,
                'followings'
            )
        );
    }
    public function toggleFollow(
        $id,
    ) {
        $authUser = Auth::user();
        $targetUser = User::findOrFail(
            $id,
        );

        $isFollowing = $authUser->followings()->where('followed_id', $targetUser->id)->exists();

        if ($isFollowing) {
            $authUser->followings()->detach($targetUser->id);
            $message = 'تم إلغاء المتابعة';
        } else {
            $authUser->followings()->attach($targetUser->id);
            $message = 'تمت المتابعة';
            $targetUser->notify(
                new NewFollowerNotification(
                    $authUser,
                ),
            );
        }

        return response()->json(['message' => $message]);
    }

    public function suggested(Request $request)
    {
        $authUser = $request->user();

        $companies = User::where(
            'userable_type',
            Company::class,
        )
            ->whereDoesntHave('followers', function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id);
            })
            ->with('userable')
            ->get();

        return response()->json(
            $companies->map(
                function ($company) {
                    $companyData = $company->userable;
                    return [
                        'id' => $company->id,
                        'type' => 'Company',
                        'name' => $companyData?->name ?? 'اسم غير متوفر',
                        'image' => $companyData->image ?? '',
                    ];
                },
            )
        );
    }
    private function getUserFromRequest(
        Request $request,
    ) {
        if (filter_var(
            $request->my_data,
            FILTER_VALIDATE_BOOLEAN,
        )) {
            return Auth::user();
        }

        return $request->user_id ? User::findOrFail(
            $request->user_id,
        ) : Auth::user();
    }
}
