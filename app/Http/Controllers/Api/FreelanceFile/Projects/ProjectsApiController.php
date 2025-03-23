<?php

namespace App\Http\Controllers\Api\FreelanceFile\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FreelanceFile\Projects\ProjectResource;
use Illuminate\Support\Facades\Auth;
use App\Helpers\uploadImageHelper;
use App\Models\FreelanceFile\Project\Project;

class ProjectsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
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
    public function get(Request $request)
    {
        try {
            $query = Project::with(
                'user.userable',
            );
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

            $projects = $query->paginate(
                5,
            );
            return successRes(
                paginateRes(
                    $projects,
                    ProjectResource::class,
                    'projects'
                )
            );
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }

    public function checkAndHandleRequest(
        Request $request,
    ) {
        try {
            //! التحقق من المستخدم المصادق عليه
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            //! التحقق مما إذا كان الحقل id موجودًا ويحمل قيمة صالحة
            return $request->filled('id')
                ? $this->updateProject(
                    $request,
                    $user,
                )
                : $this->createProject(
                    $request,
                    $user,
                );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createProject(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $imagePath = $request->hasFile('image')
                ? uploadImageHelper::uploadImage(
                    $request,
                    $user,
                    'projects',
                    'image',
                )
                : null;
            $project = Project::create(
                [
                    'user_id' => $user->id,
                    'image' => $imagePath ??  'image',
                    'name' => $request->name ?? '',
                    'description' => $request->description ?? '',
                    'price' => $request->price ?? 0,
                    'specialization_id' => $request->specialization_id ?? 1,
                ],
            );
            return successRes(
                new ProjectResource(
                    $project->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    private function updateProject(
        Request $request,
        $user,
    ) {
        try {
            $project = Project::where('id', $request->id)
                ->where('user_id', $user->id)
                ->first();
            //! الاحتفاظ بالصورة القديمة إذا لم يتم رفع صورة جديدة
            $newImagePath = $project->image;
            if ($request->hasFile('image')) {
                $newImagePath = uploadImageHelper::updateImage(
                    $request,
                    $user,
                    'projects',
                    $project->image
                );
            }
            $project->update(
                [
                    'name' => $request->name ?? $project->name,
                    'description' => $request->description ?? $project->description,
                    'price' => $request->price ?? $project->price,
                    'image' => $newImagePath ?? $project->image,
                ],
            );
            return successRes(
                new ProjectResource(
                    $project->fresh(),
                ),

            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function destroy(Project $project) {}
}
