<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification\Notification;

class NotificationsDashController extends Controller
{

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
                return $this->failureRes();
        }
    }

    public function store(
        Request $request,
    ) {
        $notificationData = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $request->input('user_id'),
            'created_at' => now()->toDateTimeString()
        ];
        return response()->json($response);
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
        return $this->successRes($notification);
    }
}
