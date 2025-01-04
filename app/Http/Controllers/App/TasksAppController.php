<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Task;
use App\Models\TaskProof;

class TasksAppController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                if ($id) {
                    return $this->getTaskDetails(
                        $request,
                        $id,
                    );
                } else {
                    return $this->getTasks(
                        $request,
                    );
                }
            case 'POST':
                return $this->proofTask(
                    $request,
                    $id,
                );
            default:
                return $this->failureResponse();
        }
    }

    public function getTasks(): JsonResponse
    {
        try {
            $tasks = Task::where(
                [
                    'status' => true,
                ],
            )->get();
            return successResponse(
                $tasks,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                'Failed to fetch tasks: ' . $e->getMessage(),
            );
        }
    }

    public function getTaskDetails(
        Request $request,
        $id,
    ) {
        try {
            $task = Task::where(
                [
                    'id' => $id,
                    'status' => true,
                ],
            )->first();
            $user = Auth::guard('sanctum')->user();
            return successResponse(
                [
                    'task' => $task,
                    'completed' => TaskProof::where(
                        [
                            'user_id' => $user->id,
                            'task_id' => $task->id,
                        ],
                    )->count() > 0
                ],
            );
        } catch (\Exception $e) {
            return failureResponse(
                'Failed to fetch tasks: ' . $e->getMessage(),
            );
        }
    }

    protected function proofTask(
        Request $request,
    ) {
        try {
            return failureResponse();
            $user = Auth::guard('sanctum')->user();
            $task = Task::withCount(
                [
                    'users' => fn(
                        $builder,
                    ) => $builder->where(
                        'user_id',
                        $user->id,
                    )
                ],
            )
                ->where(
                    [
                        'id' => $request->id,
                        'status' => true,
                    ],
                )->first();
            if ($task->users_count != 0) {
                return failureResponse(
                    __('You already have completed this task'),
                );
            }
            $image = uploadImage(
                $request->file(
                    'image',
                ),
                'task_proofs',
            );
            TaskProof::create(
                [
                    'status' => "pending",
                    'image' => $image,
                    'task_id' => $task->id,
                    'user_id' => $user->id
                ],
            );
            return successResponse(
                [
                    'completed' => true,
                    'task' => $task
                ],
            );
        } catch (\Exception $e) {
            return failureResponse(
                'Failed to fetch tasks: ' . $e->getMessage(),
            );
        }
    }
}
