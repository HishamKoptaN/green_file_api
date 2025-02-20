<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Models\Permission as AppPermission;
use App\Models\Role;

class RoleAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function index()
    {
        try {
            $roles = Role::all();
            $permissions = AppPermission::select('id', 'name')->get();
            return response()->json([
                'status' => true,
                'roles' => $roles,
                'permissions' => $permissions // تغيير اسم المفتاح لتطابق الصيغة
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve roles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
