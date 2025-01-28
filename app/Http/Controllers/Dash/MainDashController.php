<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MainDashController extends Controller
{
    public function handleMain(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->getUserPermissions($request);
            case 'POST':
                return $this->uploadFile($request);

            case 'PUT':
                return $this->updateFile($request);
            case 'DELETE':
                return $this->deleteFile($request);
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method']);
        }
    }
    public function getUserPermissions(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $permissions = DB::table('user_has_permissions')
                ->join('permissions', 'user_has_permissions.permission_id', '=', 'permissions.id')
                ->where('user_has_permissions.user_id', $user->id)
                ->pluck('permissions.name');
            return response()->json([
                'status' => true,
                'permissions' => $permissions
            ]);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
}
