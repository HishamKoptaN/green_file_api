<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function getAccountsUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }
        $accountInfo = $user->account_info;
        return response()->json([
            'status' => true,
            'accountInfo' => $accountInfo,
        ],
         200,
    );
    }

    public function updateAccountsInfo(
        Request $request,
    ) {
        $user = Auth::user();
        $user->account_info = $request->accountInfo;
        return response()->json([
            'status' => true,
        ], 200);
    }
    public function index(
        Request $request,
    ) {
        $user = $request->user();
        return response()->json(
            [
                'status' => true,
                'user' => $user
            ],
        );
    }


}
