<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Deposit;
use App\Models\Rate;
use App\Models\Account;

class DepositsAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->getDeposits(
                    $request,
                );
            case 'POST':
                return $this->depositMoney(
                    $request,
                );
            default:
                return response()->json(
                    ['status' => false, 'message' => 'Invalid request method'],
                    405,
                );
        }
    }
    public function getDeposits(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => false,
                'error' => __('User not authenticated')
            ], 401);
        }
        $user = Auth::guard('sanctum')->user();
        $deposits = Deposit::with('currency:id,name')
            ->where('user_id', $user->id)->get();
        $deposits->each(
            function ($deposit) {
                $deposit->currency->makeHidden(['id']);
            },
        );
        return response()->json(
            [
                'status' => true,
                'deposits' => $deposits,
            ],
        );
    }
    public function getEmployeeAccounts(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $employee = DB::table('role_users')
            ->join(
                'users',
                'role_users.user_id',
                '=',
                'users.id',
            )
            ->where(
                'role_users.role_id',
                4,
            )
            ->select('users.*')
            ->first();
        if (!$employee) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No employee found with role_id 4',
                ],
            );
        }
        $userPlan = $user->userPlan;
        $to_binance_rates = Rate::where(
            'plan_id',
            $userPlan->plan_id,
        )
            ->where(
                'to',
                1,
            )
            ->get()
            ->map(
                function ($rate) {
                    return [
                        'price' => $rate->price,
                        'updated_at' => $rate->updated_at,
                        'currency_name' => $rate->fromCurrency->name,
                        'from' => $rate->fromCurrency->id,
                    ];
                },
            );
        if (
            $employee->online_offline === 'online'
        ) {
            $accounts = Account::where(
                'user_id',
                $employee->id,
            )
                ->with(
                    'currency:id,name',
                )
                ->get();
            return successResponse(
                [
                    'user_plan' => $user->plan_id,
                    'employee_id' => $employee->id,
                    'account_info' => $accounts,
                    'to_binance_rates' => $to_binance_rates,
                ],
            );
        } else {
            return failureResponse(
                'Employee is offline',
            );
        }
    }

    public function depositMoney(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $image = uploadImage(
                $request->file('image'),
                'deposits',
            );
            Deposit::create(
                [
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'image' =>   $image,
                    'method' => $request->method,
                    'employee_id' => $request->employee_id,
                ],
            );
            return successResponse();
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
