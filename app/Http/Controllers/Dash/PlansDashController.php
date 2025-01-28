<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Account;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Dash\ModelNotFoundException;

class PlansDashController extends Controller
{
    use ApiResponseTrait;
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->post(
                    $request,
                    $id,
                );
            case 'PATCH':
                return $this->edit(
                    $request,
                    $id,
                );
            case 'PUT':
                return $this->add(
                    $request,
                );
            case 'DELETE':
                return $this->delete(
                    $request,
                );
            default:
                return $this->failureResponse(
                    'Invalid request method',
                );
        }
    }
    protected function get()
    {
        try {
            $plans = Plan::all();
            $plans_admin = User::whereHas('roles', function ($query) {
                $query->where('name', 'plans-admin');
            })->first();
            if (!$plans_admin) {
                return $this->failureResponse('No plans-admin user found');
            }
            $accounts = Account::where('user_id', $plans_admin->id)
                ->with('currency:id,name')
                ->get();

            $accounts->each(function ($account) {
                $account->currency->makeHidden(['id']);
            });

            return $this->successResponse([
                'plans' => $plans,
                'accounts' => $accounts
            ]);
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    protected function post(Request $request)
    {
        $request->validate(
            [
                "name" => "required|string|max:100",
                "amount" => "required|numeric|between:0,5000",
                "daily_transfer_count" => "required|numeric|min:1",
                "monthly_transfer_count" => "required|numeric|min:1",
            ],
        );

        try {
            $plan = Plan::create(
                [
                    "name" => $request->name,
                    "amount" => $request->amount,
                    "user_amount_per_referal" => 0,
                    "amount_after_count" => 0,
                    "count" => 0,
                    "refered_amount" => 0,
                    "discount" => 0,
                    "discount_type" => 0,
                    "daily_transfer_count" => $request->daily_transfer_count,
                    "monthly_transfer_count" => $request->monthly_transfer_count,
                ],
            );
            return $this->successResponse(
                $plan,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }



    protected function edit(
        Request $request,
        $id,
    ) {
        try {
            $plan = Plan::findOrFail(
                $request->id,
            );
            $plan->update(
                [
                    "name"                   => $request->name,
                    "amount"                 => $request->amount,
                    "transfer_commission"                 => $request->transfer_commission,
                    "discount"                  => $request->discount,
                    "daily_transfer_count"          => $request->daily_transfer_count,
                    "monthly_transfer_count"   => $request->monthly_transfer_count,
                    "max_transfer_count" => $request->max_transfer_count,
                ],
            );
            return $this->successResponse(
                $plan,
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failureResponse(
                __('Plan not found'),
                404,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
    // protected function deletePlan(Request $request)
    // {
    //     try {
    //         $id = $request->input('id');
    //         $plan = Plan::findOrFail($id);
    //         $plan->delete();
    //         return response()->json([
    //             'status' => true,
    //             'message' => __('Plan deleted successfully'),
    //         ], 200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => __('Plan not found'),
    //         ], 404);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => __('Failed to delete plan'),
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
