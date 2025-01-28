<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Currency;
use App\Traits\ApiResponseTrait;

class AccountsDashController extends Controller
{


    use ApiResponseTrait;
    public function handleRequest(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->getAccounts($request);
            case 'PATCH':
                return $this->updateAccountNumber($request);
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method']);
        }
    }
    public function getAccounts()
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return $this->failureResponse(
                    'User not authenticated',
                );
            }
            $accounts = Account::where(
                'user_id',
                $user->id,
            )
                ->with(
                    [
                        'currency:id,name',
                    ],
                )
                ->get()
                ->map(
                    function ($account) {
                        $account->currency->makeHidden('id');
                        $account->user_name = $account->user->name;
                        return $account;
                    },
                );
            $user_status = $user->online_offline === 'online';
            return $this->successResponse(
                [
                    'accounts' => $accounts,
                    'user_status' => $user_status,
                ],
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }

    public function updateAccountNumber(Request $request)
    {
        try {
            $validatedData = $request->validate(
                [
                    'id' => 'required|exists:accounts,id',
                    'account_number' => 'required|string',
                    'comment' => 'required|string',
                ],
            );
            $account = Account::find(
                $validatedData['id'],
            );
            $account->update(
                [
                    'account_number' => $validatedData['account_number'],
                    'comment' => $validatedData['comment'],
                ],
            );
            return $this->successResponse(
                $account,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->failureResponse(
                $e->getMessage(),
                422,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
}
