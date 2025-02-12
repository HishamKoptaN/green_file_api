<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RolesDashController extends Controller
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
                );
            case 'DELETE':
                return $this->delete(
                    $id
                );
            default:
                return failureRes();
        }
    }
    public function get()
    {
        try {

            $user = Auth::guard(
                'sanctum'
            )->user();
            if (!$user) {
                return failureRes(
                    'User not authenticated',
                    401,
                );
            }
            $user_roles = $user->roles;
            $roles = Role::with('permissions')->get();
            return successRes(
                [
                    'user_roles' => $user_roles,
                    'roles' => $roles
                ]
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function post(
        Request $request,
    ) {
        $request->validate(
            [
                'name' => 'required|string|unique:roles,name',
            ],
        );
        try {
            $role = Role::create(
                [
                    'name' => $request->name,
                ],
            );
            return successRes(
                $role,
                201
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function patch(
        Request $request,
    ) {
        try {
            $updateRole = collect(
                $request->permissions,
            )->pluck('id')->toArray();
            $role = Role::find(
                $request->id,
            );
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions(
                $updateRole,
            );
            $role->load(
                'permissions',
            );
            return successRes(
                $role,
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }

    public function delete(
        Role $role,
    ) {
        try {
            $role->delete();
            return successRes();
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
}
