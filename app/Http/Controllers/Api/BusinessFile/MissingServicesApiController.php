<?php

namespace App\Http\Controllers\Api\BusinessFile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\BusinessFile\MissingServiceResource;
use App\Models\BusinessFile\MissingService;

class MissingServicesApiController extends Controller
{
    public function myServices()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return failureRes(
                'Unauthorized',
                401
            );
        }
        $missing_services = MissingService::where('user_id', $user->id)->latest()->get();
        return successRes(
            paginateRes(
                $missing_services,
                MissingServiceResource::class,
                'missing_services',
            )
        );
    }
    public function get(
        Request $request,
    ) {
        try {
            $query = MissingService::with('user.userable');
            //! إذا تم إرسال my_data=true، يتم جلب منشورات المستخدم المسجل دخول فقط
            if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                $query->where(
                    'user_id',
                    Auth::id(),
                );
            } else {
                //! إذا لم يتم إرسال my_data، يمكن استخدام user_id إن وجد
                $query->when($request->user_id, function ($q, $userId) {
                    return $q->where('user_id', $userId,);
                });
            }
            $services = $query->paginate(10);
            return successRes(
                paginateRes(
                    $services,
                    MissingServiceResource::class,
                    'services'
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
   
}
