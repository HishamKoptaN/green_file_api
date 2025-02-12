<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\StatusResource;
use App\Models\Status;

class StatusesApiController extends Controller
{
    public function handleReq(
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
    public function get()
    {
        try {
            $statuses = Status::get();
            return successRes(
                StatusResource::collection(
                    $statuses,
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
}
