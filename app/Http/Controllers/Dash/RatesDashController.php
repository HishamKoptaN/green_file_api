<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Rate;

class RatesDashController extends Controller
{
    use ApiResponseTrait;
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->getRates();
            case 'PATCH':
                return $this->patch(
                    $request,
                );

            default:
                return $this->failureResponse();
        }
    }
    protected function getRates()
    {
        $rates = Rate::all();
        $currencies = Currency::all()->keyBy('id');
        $ratesWithDetails = $rates->map(
            function ($rate) use ($currencies) {
                $fromCurrency = $currencies->get($rate->from)->name ?? 'Unknown';
                $toCurrency = $currencies->get($rate->to)->name ?? 'Unknown';
                return (object) [
                    'id' => $rate->id,
                    'status' => $rate->status,
                    'plan_id' => $rate->plan_id,
                    'from' => $rate->from,
                    'to' => $rate->to,
                    'price' => $rate->price,
                    'from_from' => $fromCurrency,
                    'to_from' => $toCurrency,
                ];
            },
        );
        $groupedRates = $ratesWithDetails
            ->groupBy('from_from')
            ->map(function ($groupedByCurrency, $fromCurrency) {
                $plansRates = $groupedByCurrency->groupBy('plan_id')->map(function ($ratesByPlan) {
                    return $ratesByPlan->values();
                })->values();

                return [
                    'from_currency' => $fromCurrency,
                    'plans_rates' => $plansRates,
                ];
            })
            ->values();

        return $this->successResponse(
            $groupedRates,
        );
    }
    protected function patch(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'price' => 'sometimes|numeric',
            'status' => 'sometimes|boolean',
        ]);

        $rateId = $request->input('id');
        // البحث عن السجل المطلوب
        $rate = Rate::where('plan_id', 3)
            ->where('id', $rateId)
            ->first();

        if (!$rate) {
            return response()->json(['message' => 'Rate not found'], 404);
        }
        // تحديث status إذا تم إرساله
        if ($request->has('status')) {
            $status = $request->input('status') ? 1 : 0;
            $rate->status = $status;
        }
        // تحديث price إذا تم إرساله
        if ($request->has('price')) {
            $newValue = $request->input('price');
            $rate->price = $newValue;

            $to = $rate->to;
            $from = $rate->from;

            // تحديث الحقول المرتبطة بخطط أخرى
            Rate::where('plan_id', 1)
                ->where('from', $from)
                ->where('to', $to)
                ->update([
                    'price' => $newValue * 1.01,
                    'status' => $rate->status,
                ]);

            Rate::where('plan_id', 2)
                ->where('from', $from)
                ->where('to', $to)
                ->update([
                    'price' => $newValue * 1.005,
                    'status' => $rate->status,
                ]);
        }

        // حفظ التحديثات
        $rate->save();

        return response()->json(
            $rate,
        );
    }
}
