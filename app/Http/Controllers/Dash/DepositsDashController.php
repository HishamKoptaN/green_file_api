<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Balance;
use App\Traits\ApiResponseTrait;

class DepositsDashController extends Controller
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
                return $this->failureResponse();
        }
    }
    public function get()
    {
        try {
            $deposits = Deposit::with(
                [
                    'user',
                    'employee:id,first_name',
                    'currency:id,name'
                ],
            )->orderBy('created_at', 'desc')->get();
            $deposits->each(
                function ($deposit) {
                    $deposit->user->makeHidden(['id']);
                    $deposit->currency->makeHidden(['id']);
                    $deposit->employee->makeHidden(['id']);
                },
            );
            return $this->successResponse(
                $deposits,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                'حدث خطأ أثناء جلب بيانات الإيداع: ' . $e->getMessage(),
            );
        }
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
            $deposit = Deposit::find(
                $request->id,
            );
            $deposit->status = $request->status;
            if ($request->status === 'accepted') {
                $deposit->approved_at = now();
            }
            $deposit->save();
            if ($request->status === 'accepted') {
                $balance = Balance::where(
                    'user_id',
                    $deposit->user_id,
                )->first();
                if ($balance) {
                    $balance->suspended_balance += $deposit->amount;
                    $balance->save();
                }
            }
            return $this->successResponse(
                $deposit,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage()
            );
        }
    }
}
