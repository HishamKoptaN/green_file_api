<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;


class TransfersDashController extends Controller
{
    use ApiResponseTrait;
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'PATCH':
                return $this->patch(
                    $request,
                    $id,
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
    public function get()
    {






        $transfers = Transfer::with(
            [
                'user:id,first_name',
                'employee:id,first_name',
                'senderCurrency:id,name',
                'receiverCurrency:id,name'
            ],
        )->orderBy('created_at', 'desc')->get();
        $transfers->each(
            function ($transfer) {
                $transfer->user->makeHidden(
                    [
                        'id',
                    ],
                );
                $transfer->employee->makeHidden(
                    [
                        'id',
                    ],
                );
                $transfer->senderCurrency->makeHidden(
                    [
                        'id',
                    ],
                );
                $transfer->receiverCurrency->makeHidden(
                    ['id',],
                );
            },
        );
        return  $this->successResponse(
            $transfers,
        );
    }
    public function patch(
        Request $request,
        $id,
    ) {
        $request->validate(
            [
                'status' => 'required|in:accepted,rejected',
            ],
        );
        try {
            $transfer = Transfer::find(
                $request->id,
            );
            $transfer->status = $request->status;
            $transfer->save();
            if ($request->status === 'accepted') {
                $user = User::find(
                    $transfer->user_id,
                );
                if ($user) {
                    $user->balance -= $transfer->amount;
                    $user->save();
                }
            }
            $transfer = Transfer::with(
                [
                    'user:id,first_name',
                    'employee:id,first_name',
                    'senderCurrency:id,name',
                    'receiverCurrency:id,name',
                ],
            )->find(
                $request->id,
            );
            return $this->successResponse(
                $transfer,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage()
            );
        }
    }
}
