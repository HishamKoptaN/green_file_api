<?php

namespace App\Http\Controllers\Api\BusinessFile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\BusinessFile\ServiceResource;
use App\Models\BusinessFile\Service;
use App\Helpers\uploadImageHelper;

class ServicesApiController extends Controller
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
                return $this->checkAndHandleRequest(
                    $request,
                );
            default:
                return failureRes();
        }
    }
    public function myServices()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return failureRes(
                'Unauthorized',
                401
            );
        }
        $services = Service::where('user_id', $user->id)->latest()->get();
        return successRes(
            paginateRes(
                $services,
                ServiceResource::class,
                'services',
            )
        );
    }
    public function get(Request $request)
    {
        try {


            $query = Service::with('user.userable');
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
                    ServiceResource::class,
                    'services'
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }


    public function search(
        Request $request,
    ) {
        $query = $request->input('query');
        $services = Service::where('name', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->get();

        return response()->json(
            [
                'success' => true,
                'data' => $services,
            ],
        );
    }
    public function checkAndHandleRequest(Request $request)
    {
        try {
            //! التحقق من المستخدم المصادق عليه
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            //! التحقق مما إذا كان الحقل id موجودًا ويحمل قيمة صالحة
            return $request->filled('id')
                ? $this->updateService($request, $user)
                : $this->createService($request, $user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createService(
        Request $request,
        $user
    ) {
        try {
            $imagePath = $request->hasFile('image')
                ? uploadImageHelper::uploadImage(
                    $request,
                    $user,
                    'services',
                    'image',
                )
                : null;
            $service = Service::create(
                [
                    'user_id' => $user->id,
                    'image' => $imagePath ??  'image',
                    'name' => $request->name ?? '',
                    'description' => $request->description ?? '',
                    'price' => $request->price ?? 0,
                ],
            );
            return successRes(
                new ServiceResource(
                    $service->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    private function updateService(
        Request $request,
        $user,
    ) {
        try {
            $service = Service::where('id', $request->id)
                ->where('user_id', $user->id)
                ->first();
            //! الاحتفاظ بالصورة القديمة إذا لم يتم رفع صورة جديدة
            $newImagePath = $service->image;
            if ($request->hasFile('image')) {
                $newImagePath = uploadImageHelper::updateImage(
                    $request,
                    $user,
                    'services',
                    $service->image
                );
            }
            $service->update(
                [
                    'name' => $request->name ?? $service->name,
                    'description' => $request->description ?? $service->description,
                    'price' => $request->price ?? $service->price,
                    'image' => $newImagePath ?? $service->image,
                ],
            );
            return successRes(
                new ServiceResource(
                    $service->fresh(),
                ),

            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
