<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission as SpatiePermission;

class PermissionsDashController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->post(
                    $request,
                    $id
                );
            case 'PATCH':
                return $this->patch(
                    $request,
                    $id
                );
            case 'DELETE':
                return $this->delete(
                    $id
                );
            default:
                return $this->failureResponse();
        }
    }


    public function get()
    {
        try {
            $permissions = Permission::all();
            return successResponse(
                $permissions,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
    public function patch(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $permissions = $request->input('permissions');
        $user->syncPermissions($permissions);
        return response()->json(['message' => 'Permissions updated successfully.']);
    }
    public function addPermissionsToUser(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $permissions = $request->input('permissions');
        if (!is_array($permissions) || empty($permissions)) {
            return response()->json(['message' => 'Permissions should be provided as a non-empty array'], 400);
        }
        foreach ($permissions as $permissionName) {
            $permission = Permission::findOrCreate($permissionName);
            $user->givePermissionTo($permission);
        }
        return response()->json(['message' => 'Permissions added successfully']);
    }
}
