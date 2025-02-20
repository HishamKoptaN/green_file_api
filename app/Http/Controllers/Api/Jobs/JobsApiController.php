<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Job\JobCollection;
use App\Http\Resources\Job\JobResource;
use App\Models\Job\Job;
use Illuminate\Support\Facades\Auth;

class JobsApiController extends Controller
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
            $jobs = Job::with('company')->paginate(10);;
            return successRes(
                new  JobCollection(
                    $jobs,
                ),
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
            $comment = Job::create(
                [
                    'post_id' => $request->id,
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                ],
            );
            return successRes(
                new JobResource(
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
}
