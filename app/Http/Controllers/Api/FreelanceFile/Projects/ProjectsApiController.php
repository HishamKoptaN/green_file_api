<?php

namespace App\Http\Controllers\Api\FreelanceFile\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FreelanceFile\Projects\ProjectResource;
use Illuminate\Support\Facades\Auth;
use App\Models\FreelanceFile\Project\Project;

class ProjectsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->create(
                    $request,
                );
            default:
                return failureRes();
        }
    }
    public function get()
    {
        try {
            $projects = Project::paginate(10);;
            return successRes(
                paginateRes(
                    $projects,
                    ProjectResource::class,
                    'projects',
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function create(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(
                    [
                        'error' => 'Unauthorized',
                    ],
                    401,
                );
            }
            $comment = Project::create(
                [
                    'post_id' => $request->id,
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                ],
            );
            return successRes(
                new ProjectResource(
                    $comment->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }

    public function destroy(Project $project) {}
}
