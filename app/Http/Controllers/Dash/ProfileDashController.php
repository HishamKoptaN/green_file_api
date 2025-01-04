<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileDashController extends Controller
{
    public function handleProfile(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->getStatus(
                    $request,
                );
            case 'PATCH':
                return $this->uploadFile(
                    $request,
                );
            case 'POST':
                return $this->uploadFile(
                    $request,
                );
            case 'PUT':
                return $this->updateStatus(
                    $request,
                );
            case 'DELETE':
                return $this->deleteFile(
                    $request,
                );
            default:
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Invalid request method',
                    ],
                );
        }
    }
    public function getStatus(
        Request $request,
    ) {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        $user_status = $user->online_offline === '1';
        return response()->json([
            'status' => true,
            'user_status' => $user_status,
        ], 200);
    }
    public function updateStatus(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
            ], 401);
        }
        $currentStatus = $user->online_offline;
        $user->online_offline = $currentStatus === 'online' ? 'offline' : 'online';
        $user->save();
        return response()->json([
            'status' => true,
            'new_status' => $user->online_offline,
        ]);
    }
}
