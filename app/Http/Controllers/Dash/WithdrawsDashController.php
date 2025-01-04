<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\Chat;
use App\Traits\ApiResponseTrait;

class WithdrawsDashController extends Controller
{
    use ApiResponseTrait;
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
            default:
                return $this->failureResponse();
        }
    }
    public function get()
    {
        try {
            $withdraws = Withdraw::with(
                [
                    'user:id,first_name,account_number,plan_id',
                    'currency:id,name'
                ],
            )->orderBy('created_at', 'desc')->get();
            $withdraws->each(
                function ($withdraw) {
                    $withdraw->user->makeHidden(['id']);
                    $withdraw->currency->makeHidden(['id']);
                },
            );
            return $this->successResponse(
                $withdraws,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage()
            );
        }
    }

    public function patch(
        Request $request,
    ) {
        $request->validate(
            [
                'status' => 'required|in:accepted,rejected',
            ],
        );
        try {
            $withdraw = withdraw::find(
                $request->id,
            );
            $withdraw->status = $request->status;
            $withdraw->save();
            if ($request->status === 'accepted') {
                $user = User::find($withdraw->user_id);
                if ($user) {
                    $user->balance -= $withdraw->amount;
                    $user->save();
                }
            }
            return $this->successResponse(
                $withdraw,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage()
            );
        }
    }
}
