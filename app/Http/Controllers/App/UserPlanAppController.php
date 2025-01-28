<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class UserPlanAppController extends Controller
{
    public function getUserPlan($userId)
    {
        $userPlan = UserPlan::where('user_id', $userId)->with('plan')->first();

        if (!$userPlan) {
            return response()->json(['message' => 'User does not have an assigned plan', 'status' => false]);
        }

        return response()->json(['message' => 'User plan retrieved successfully', 'data' => $userPlan, 'status' => true]);
    }

    public function assignPlanToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $userPlan = UserPlan::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]
        );

        return response()->json(['message' => 'Plan assigned successfully', 'data' => $userPlan, 'status' => true]);
    }
}
