<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transfer;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;

class TransferAppController extends Controller
{
    public function handleRequest(
        Request $request,
        $account_number = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $account_number,
                );
            case 'POST':
                return $this->transferMoney(
                    $request,
                    $account_number,
                );
            default:
                return failureResponse(
                    'Invalid request method',
                    405,
                );
        }
    }
    protected function get(
        $account_number,
    ) {
        $user = $this->findUserByAccount(
            $account_number,
        );
        if (!$user) {
            return failureResponse(
                'User not found',
                404,
            );
        }
        return successResponse(
            [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ],
        );
    }
    public function transferMoney(
        Request $request,
    ) {
        $sender_user = Auth::guard('sanctum')->user();
        $recived_user = User::where(
            'account_number',
            $request->account_number,
        )->first();
        if (!$recived_user) {
            return failureResponse(
                'Recipient account not found',
                404,
            );
        }
        // رصيد المستخدم الراسل
        $sender_user_balance = Balance::where(
            'user_id',
            $sender_user->id,
        )->first();
        if (
            !$sender_user_balance || $sender_user_balance->available_balance < $request->amount
        ) {
            return failureResponse(
                'You don\'t have enough balance',
                400,
            );
        }
        $recived_user_balance = Balance::firstOrCreate(
            [
                'user_id' => $recived_user->id,
            ],
        );
        // بدء معاملة قاعدة البيانات لضمان سلامة العملية
        DB::beginTransaction();
        try {
            // خصم المبلغ من رصيد الراسل
            $sender_user_balance->available_balance -= $request->amount;
            $sender_user_balance->save();
            // إضافة المبلغ إلى رصيد المستلم
            $recived_user_balance->available_balance += $request->amount;
            $recived_user_balance->save();
            DB::commit();
            return successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return failureResponse(
                'Transfer failed. Please try again.' . $e->getMessage(),
            );
        }
    }


    public function index(
        Request $request,
    ) {
        $transactions = Transfer::with(
            [
                'receiver',
                'senderCurrency',
                'receiverCurrency',
            ],
        )
            ->where(
                'user_id',
                $request->user()->id,
            )
            ->latest()
            ->get();

        return successResponse(
            [
                'transactions' => $transactions,
                'user' => $request->user(),
            ]
        );
    }

    private function findUserByAccount(
        $account_number,
    ) {
        return User::where(
            'account_number',
            $account_number,
        )->first();
    }
}
