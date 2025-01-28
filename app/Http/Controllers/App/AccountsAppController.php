<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Currency;
use App\Traits\ApiResponseTrait;

class AccountsAppController extends Controller
{

    use ApiResponseTrait;
    public function handleAccounts(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->getAccounts($request);
            case 'PATCH':
                return $this->updateAccountNumbers($request);
            default:
                return $this->failureResponse();
        }
    }
    public function getAccounts(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $userId = $user->id;
            $accounts = Account::where('user_id', $userId)->get();
            $currencies = Currency::all();
            $userCurrencyIds = $accounts->pluck('currency_id')->toArray();
            foreach ($currencies as $currency) {
                if (!in_array($currency->id, $userCurrencyIds)) {
                    Account::create(
                        [
                            'user_id' => $userId,
                            'currency_id' => $currency->id,
                            'comment' => 'Account for user ' . $userId . ' with currency ' . $currency->name,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    );
                }
            }
            $accounts = Account::where('user_id', $userId)
                ->with(['currency:id,name'])->get()
                ->map(
                    function ($account) {
                        $account->currency->makeHidden('id');
                        return $account;
                    },
                );

            return $this->successResponse(
                $accounts,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
    public function updateAccountNumbers(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'error' => 'Unauthenticated user.',
                ], 401);
            }
            $userId = $user->id;
            $accountsData = $request->all();
            if (!$accountsData || !is_array($accountsData)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Invalid data format. Expected a list of accounts.',
                ], 400);
            }
            $accountIds = collect($accountsData)->pluck('id')->toArray();
            $accounts = Account::where('user_id', $userId)
                ->whereIn('id', $accountIds)
                ->get();
            if ($accounts->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'error' => 'No valid accounts found for the user.',
                ], 404);
            }
            foreach ($accounts as $account) {
                $newData = collect($accountsData)->firstWhere('id', $account->id);
                if ($newData) {
                    $account->update(
                        [
                            'account_number' => $newData['account_number'] ?? $account->account_number,
                        ],
                    );
                }
            }

            $updatedAccounts = Account::where('user_id', $userId)
                ->with('currency:id,name')
                ->get()
                ->map(
                    function ($account) {
                        $account->currency->makeHidden('id');
                        return $account;
                    },
                );

            return $this->successResponse($updatedAccounts);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
