<?php

namespace App\Http\Controllers\App;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\Plan;
use App\Models\UserPlan;

class DashAppController extends Controller
{
    use ApiResponseTrait;

    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->post(
                    $request,
                );
            case 'PATCH':
                return $this->patch(
                    $request,
                );
            case 'PUT':
                return $this->put(
                    $request,

                );
            case 'DELETE':
                return $this->delete();
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method']);
        }
    }
    public function get()
    {
        try {
            $user = Auth::guard(
                'sanctum',
            )->user();
            $userPlan = $user->userPlan->plan;
            $exchange_rates = Currency::where(
                'id',
                '!=',
                2,
            )->get();
            $selling_rates = Rate::where(
                'plan_id',
                $userPlan->id,
            )->where(
                'to',
                2,
            )->get()->map(
                function (
                    $selling_price,
                ) {
                    return [
                        'price' => $selling_price->price,
                        'updated_at' => $selling_price->updated_at,
                        'from' => $selling_price->fromCurrency->id,
                    ];
                },
            );
            $buying_rates = Rate::where(
                'plan_id',
                $userPlan->id,
            )->where(
                'from',
                2,
            )->get()->map(
                function ($buying_price) {
                    return [
                        'price' => $buying_price->price,
                        'updated_at' => $buying_price->updated_at,
                        'to' => $buying_price->toCurrency->id,
                    ];
                },
            );
            $currencies = Currency::all();
            return $this->successResponse(
                [
                    'exchange_rates' => $exchange_rates,
                    'selling_prices' => $selling_rates,
                    'buying_prices' => $buying_rates,
                    'currencies' => $currencies,
                    'rates' =>  Rate::where('plan_id', $userPlan->id)->get(),
                    'commission' => $userPlan->transfer_commission,
                ],
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                $e->getMessage(),
            );
        }
    }
}
