<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Traits\ApiResponseTrait;

class TasksDashController extends Controller
{
    use ApiResponseTrait;

    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->post($request);
            case 'PATCH':
                return $this->patch($request, $id);
            default:
                return $this->failureResponse('Unsupported request method', 405);
        }
    }
    public function get()
    {
        try {
            $tasks = Task::all();
            return successResponse(
                $tasks,
            );
        } catch (\Exception $e) {
            return failureResponse(
                'Failed to fetch tasks: ' . $e->getMessage(),
            );
        }
    }

    public function post(Request $request)
    {
        try {
            $this->validateTask($request);
            $imageUrl = uploadImage($request->file('image'), 'tasks');
            $task = Task::create(
                [
                    'status' => $request->input('status'),
                    'name' => $request->input('name'),
                    'link' => $request->input('link'),
                    'description' => $request->input('description'),
                    'amount' => $request->input('amount'),
                    'image' => $imageUrl,
                ]
            );

            return successResponse($task);
        } catch (\Exception $e) {
            return failureResponse('Failed to create task: ' . $e->getMessage());
        }
    }

    // تحديث مهمة موجودة
    public function patch(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return failureResponse('Task not found', 404);
        }
        try {
            $this->validateTask($request, false);
            if ($request->hasFile('image')) {
                $task->image = $this->handleImageUpload($request->file('image'));
            }

            $task->update(
                [
                    'status' => $request->input('status'),
                    'name' => $request->input('name'),
                    'link' => $request->input('link'),
                    'description' => $request->input('description'),
                    'amount' => $request->input('amount'),
                ],
            );

            return $this->successResponse($task, 'Task updated successfully');
        } catch (\Exception $e) {
            return $this->failureResponse('Failed to update task: ' . $e->getMessage(), 500);
        }
    }


    // طريقة للتحقق من صحة البيانات
    private function validateTask(Request $request, $isCreate = true)
    {
        $rules = [
            'status' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
        ];

        if ($isCreate || $request->hasFile('image')) {
            $rules['image'] = 'required|file|mimes:jpeg,png,gif|max:2048'; // الحد الأقصى 2 ميجابايت
        }

        $request->validate($rules);
    }
}
