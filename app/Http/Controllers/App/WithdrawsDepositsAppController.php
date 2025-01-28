<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\plan;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdraw;

class WithdrawsDepositsAppController extends Controller
{
    public function handleWithdrawsDeposits(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->getWithdrawsAndDeposits($request);
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method'], 405);
        }
    }
    public function getWithdrawsAndDeposits(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => false,
                'error' => __('User not authenticated')
            ], 401);
        }
        $user = Auth::guard('sanctum')->user();
        try {
            $deposits = Deposit::with('currency:id,name')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($deposit) {
                    $deposit->currency->makeHidden(['id']);
                    $deposit->type = 'deposit';
                    return $deposit;
                });
            $withdraws = Withdraw::with('currency:id,name')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($withdraw) {
                    $withdraw->currency->makeHidden(['id']);
                    $withdraw->type = 'withdraw';
                    return $withdraw;
                });
            $transactions = $deposits->merge($withdraws);
            $sortedTransactions = $transactions->sortByDesc('created_at')->values();
            if ($sortedTransactions->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => __('No transactions found'),
                ], 404);
            }
            return response()->json([
                'status' => true,
                'withdraws_deposits' => $sortedTransactions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => __('Failed to retrieve transactions'),
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
