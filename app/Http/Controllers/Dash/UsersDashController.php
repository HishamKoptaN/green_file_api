<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Traits\ApiResponseTrait;

class UsersDashController extends Controller
{
    use ApiResponseTrait;
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->uploadFile(
                    $request,
                );
            case 'PUT':
                return $this->updateFile(
                    $request,
                );
            case 'PATCH':
                return $this->updateUser(
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
    protected function get(
        Request $request,
    ) {
        try {
            $users = User::with(
                [
                    'roles',
                    'balance',
                    'userPlan',
                ],
            )->get();
            return $this->successResponse(
                $users,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
    protected function updateUser(
        Request $request,
    ) {
        try {
            $user = User::find(
                $request->id,
            );
            $user->load(
                'balance',
            );
            if ($request->has(
                'balance',
            )) {
                $user->balance->update(
                    $request->input(
                        'balance',
                    ),
                );
            }
            $updateRoles = collect(
                $request->roles,
            )->pluck(
                'id',
            )->toArray();
            $user->roles()->sync(
                $updateRoles,
            );
            $dataToUpdate = [
                "status" => $request->status,
                "plan_id" => $request->plan_id,
                "comment" => $request->comment,
            ];
            $user->update(
                $dataToUpdate,
            );
            $userWithRoles = $user->load(
                'roles.permissions',
            );
            return $this->successResponse(
                $userWithRoles
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
}
