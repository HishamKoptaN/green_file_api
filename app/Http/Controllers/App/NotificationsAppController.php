<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponseTrait;

class NotificationsAppController extends Controller
{
    use ApiResponseTrait;
    public function handleRequest(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->store(
                    $request,
                );
            case 'PUT':
                return $this->updateFile(
                    $request,
                );
            case 'DELETE':
                return $this->deleteFile(
                    $request,
                );
            default:
                return $this->failureResponse();
        }
    }
    public function get(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $userId = $user->id;
        $notifications = Notification::where('public', true)
            ->orWhereHas(
                'users',
                function ($query) use ($user) {
                    $query->where(
                        'user_id',
                        $user->id,
                    );
                },
            )
            ->get();

        return $this->successResponse(
            $notifications,
        );
    }
}
