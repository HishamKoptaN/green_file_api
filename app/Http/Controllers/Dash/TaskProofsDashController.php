<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskProof;
use App\Models\User;

class TaskProofsDashController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->getTaskProofs(
                    $request,
                );
            case 'PATCH':
                return $this->updateTaskStatus(
                    $request,
                    $id,
                );
            default:
                return $this->failureResponse();
        }
    }
    public function getTaskProofs()
    {
        try {
            $taskProofs = TaskProof::with(
                [
                    'user:id,first_name',
                ],
            )->orderBy(
                'created_at',
                'desc',
            )->get();
            $taskProofs->each(
                function ($taskProof) {
                    $taskProof->user->makeHidden(
                        [
                            'id',
                        ],
                    );
                },
            );
            return successResponse(
                $taskProofs,
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage()
            );
        }
    }
    public function updateTaskStatus(
        Request $request,
    ) {
        $request->validate(
            [
                'status' => 'required|in:rejected,accepted',
            ],
        );
        try {
            $task = TaskProof::where(
                'id',
                $request->id,
            )
                ->with(
                    [
                        'user:id,first_name',
                    ],
                )->first();
            $task->status = $request->status;
            $task->save();
            if (
                $request->status === 'accepted'
            ) {
                $user = User::find($task->user_id);
                if ($user) {
                    $user->balance += $task->amount;
                    $user->save();
                }
            }
            return successResponse(
                $task,
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage()
            );
        }
    }
}
