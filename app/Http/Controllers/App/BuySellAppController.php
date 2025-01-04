<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transfer;
use App\Models\AppControl;
use App\Models\Plan;
use App\Models\Rate;
use App\Models\RoleUser;
use App\Models\User;

class BuySellAppController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                if ($id === null) {
                    return $this->get(
                        $request,
                        $id,
                    );
                } else {
                    return $this->getReceivedAccount(
                        $id,
                    );
                }
            case 'POST':
                return $this->store(
                    $request,
                );
            default:
                return failureResponse();
        }
    }
    public function get()
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $userPlan = $user->userPlan;

            $appController = AppControl::findOrFail(
                1,
            );
            $buy_sell_status = (bool) $appController->status;
            $buy_sell_message = $appController->message;
            $total_monthly_transfers = Transfer::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->sum('amount');
            $total_daily_transfers = Transfer::where('user_id',  $user->id)->whereDate('created_at', Carbon::today())->sum('amount');
            $max_transfer_count = Plan::where('id', $userPlan->plan_id,)->value('max_transfer_count');
            $monthly_transfer_count = Plan::where('id', $userPlan->plan_id,)->value('monthly_transfer_count');
            $daily_transfer_count = Plan::where('id', $userPlan->plan_id,)->value('daily_transfer_count');
            $currencies = Currency::select('id', 'name', 'name_code', 'comment')->get();
            $accounts = Account::with('currency:id,name')->where('user_id', $user->id)->get();
            $rates = Rate::where('plan_id', $userPlan->plan_id,)->get();
            return successResponse(
                [
                    'buy_sell_status' => $buy_sell_status,
                    'buy_sell_message' => $buy_sell_message,
                    'total_monthly_transfers' => $total_monthly_transfers,
                    'total_daily_transfers' => $total_daily_transfers,
                    'max_transfer_count' => $max_transfer_count,
                    'monthly_transfer_count' => $monthly_transfer_count,
                    'daily_transfer_count' => $daily_transfer_count,
                    'currencies' => $currencies,
                    'rates' => $rates,
                    'accounts' => $accounts,

                ],
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }

    public function getReceivedAccount(
        $id,
    ) {
        try {
            $employee =
                RoleUser::where(
                    'role_id',
                    4,
                )->first();
            if (!$employee) {
                return failureResponse(
                    'Employee with role ID 4 not found.',
                    404,
                );
            }
            $account = $employee->user->account()->where(
                'currency_id',
                $id,
            )->with('user')

                ->first();
            if (!$account) {
                return failureResponse(
                    $id,
                    404,
                );
            }
            return successResponse(
                $account,
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }

    public function store(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $image = uploadImage(
                $request->file('image'),
                'transfers',
            );
            Transfer::create(
                [
                    'user_id' => $user->id,
                    'sender_currency_id' => $request->sender_currency_id,
                    'receiver_currency_id' => $request->receiver_currency_id,
                    'amount' => $request->amount,
                    'net_amount' => $request->net_amount,
                    'rate' => $request->rate,
                    'receiver_account' => $request->receiver_account,
                    'employee_id' => $request->employee_id,
                    'image' => $image,
                ],
            );
            return successResponse(
                null,
                201
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return failureResponse(
                $e->errors(),
                422,
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }
}
