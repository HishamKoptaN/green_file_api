<?php

namespace App\Http\Controllers\Api\Social;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Status\StatusCollection;
use App\Models\Social\Status\Status;

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
            $statuses = Status::with('user')->paginate(15);;
            return successRes(
                new StatusCollection(
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
