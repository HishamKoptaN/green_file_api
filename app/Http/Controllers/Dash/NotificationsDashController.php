<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\Notification;

class NotificationsDashController extends Controller
{

    use ApiResponseTrait;
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->post(
                    $request,
                );
            case 'PATCH':
                return $this->patch(
                    $request,
                );
            case 'DELETE':
                return $this->delete(
                    $request,
                );
            default:
                return $this->failureResponse();
        }
    }

    protected function get()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json(
            $notifications,
        );
    }

    public function post(Request $request)
    {
        $notification = Notification::create([
            'message' => $request->message,
            'notifiable_id' => $request->public ? json_encode($request->notifiable_id) : null,
            'public' => $request->public,
        ]);

        return $this->successResponse($notification);
    }
}
