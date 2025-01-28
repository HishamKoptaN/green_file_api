<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionAssignmentController extends Controller
{
    /**
     * Assign permissions to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignPermissions(Request $request, $userId)
    {
        // التحقق من أن المستخدم موجود
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // التحقق من الأذونات المرسلة في الطلب
        $permissions = $request->input('permissions', []);
        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->exists()) {
                $user->givePermissionTo($permission);
            } else {
                return response()->json(['message' => "Permission {$permission} does not exist"], 400);
            }
        }

        return response()->json(['message' => 'Permissions assigned successfully']);
    }
}
