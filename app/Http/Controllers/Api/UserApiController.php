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
        ], 200);
    }

    public function updateAccountsInfo(
        Request $request,
    ) {
        $user = Auth::user();
        $user->account_info = $request->accountInfo;
        $user->save();
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

    public function accountInfo(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
