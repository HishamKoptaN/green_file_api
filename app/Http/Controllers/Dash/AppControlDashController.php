<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\AppControl;
use Illuminate\Http\Request;

class AppControlDashController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'PATCH':
                return $this->patch(
                    $request,
                    $id,
                );
            case 'DELETE':
                return $this->delete(
                    $request,
                );
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method']);
        }
    }

    protected function get(
        Request $request,
    ) {
        try {
            $record = AppControl::all();
            return successResponse(
                $record
            );
        } catch (\Exception $e) {
            return failureResponse(
                __('Failed to update password: ') . $e->getMessage(),
            );
        }
    }

    protected function patch(
        Request $request,
    ) {
        try {
            $controller = AppControl::find(
                $request->id,
            );
            $controller->branch_name = $request->input(
                'branch_name',
            );
            $controller->message = $request->input(
                'message',
            );
            $controller->status  = $request->input(
                'status',
            );
            $controller->save();
            return successResponse(
                $controller,
            );
        } catch (\Exception $e) {
            return failureResponse(
                __('Failed to update password: ') . $e->getMessage(),
            );
        }
    }
}
