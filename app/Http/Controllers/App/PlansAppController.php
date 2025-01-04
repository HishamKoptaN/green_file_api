<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\PlanInvoice;
use App\Models\Rate;
use App\Models\User;
use App\Models\Account;
use App\Models\UserPlan;

class PlansAppController extends Controller
{

    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->proofPlan(
                    $request,
                );
            default:
                return failureResponse();
        }
    }
    protected function get()
    {
        try {
            $plans = Plan::all();
            return successResponse(
                $plans,
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }

    public function getPlansRates()
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $employee = User::findOnlineEmployee();
            $userPlan = UserPlan::where(
                'user_id',
                $user->id,
            )->first();
            $to_binance_rates = Rate::getToBinanceRates(
                $userPlan->plan_id,
            );
            if ($employee) {
                $accounts = Account::where(
                    'user_id',
                    $employee->id,
                )
                    ->with(
                        'currency:id,name',
                    )->get()
                    ->each(
                        fn($account) => $account->currency->makeHidden(
                            ['id'],
                        ),
                    );
            }
            return successResponse(
                [
                    'employee_id' => $employee->id,
                    'account_info' => $accounts,
                    'to_binance_rates' => $to_binance_rates,
                ],
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }

    protected function proofPlan(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $plan = Plan::find(
                $request->plan_id,
            );
            $image = uploadImage(
                $request->file('image'),
                'plan-proofs',
            );
            PlanInvoice::create(
                [
                    'user_id' => $user->id,
                    'plan_id' => $request->plan_id,
                    'image' => $image,
                    'currency_id' => $request->currency_id,
                    'amount' => $plan->amount,
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
                __('An unexpected error occurred: ') . $e->getMessage(),
            );
        }
    }
}
