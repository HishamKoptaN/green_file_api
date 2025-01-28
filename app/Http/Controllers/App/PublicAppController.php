<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use App\Models\Rate;
use App\Models\Plan;

class PublicAppController extends Controller
{
    public function handlePublic(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->geEmployeeAccounts($request);
            case 'POST':
                return $this->depositMoney($request);
            case 'PUT':
                return $this->depositMoney($request);
            case 'DELETE':
                return $this->deleteFile($request);
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method'], 405);
        }
    }
    public function geEmployeeAccounts(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $employee = DB::table('user_has_roles')
            ->join('users', 'user_has_roles.user_id', '=', 'users.id')
            ->where('user_has_roles.role_id', 4)
            ->select('users.*')
            ->first();
        $to_binance_rates = Rate::where([
            ['plan_id', $user->plan_id],
            ['to', 1]
        ])->get()->map(function ($rate) {
            return [
                'selling' => $rate->selling,
                'updated_at' => $rate->updated_at,
                'currency_name' => $rate->fromCurrency->name,
                'from' => $rate->fromCurrency->id,
            ];
        });
        if ($employee) {
            if ($employee->online_offline === 'online') {
                $accounts = Account::where('user_id', $employee->id)
                    ->with('currency:id,name')
                    ->get();
                $accounts->each(function ($account) {
                    $account->currency->makeHidden(['id']);
                });
                $minimum_deposit = Plan::where('id', $user->plan_id)->pluck('count')->first();

                return response()->json([
                    'status' => true,
                    'user_plan' => $user->plan_id,
                    'employee_id' => $employee->id,
                    'account_info' => $accounts,
                    'to_binance_rates' => $to_binance_rates,
                    'minimum_deposit' => $minimum_deposit,

                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Employee is offline'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No employee found with role_id 4'
            ]);
        }
    }
}
