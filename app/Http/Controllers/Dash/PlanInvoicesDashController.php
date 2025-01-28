<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Plan;
use App\Traits\ApiResponseTrait;

class PlanInvoicesDashController extends Controller
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
            case 'PATCH':
                return $this->patch(
                    $request,
                    $id,
                );
                return $this->failureResponse();
        }
    }
    public function get()
    {
        try {
            $plansInvoices = PlanInvoice::with(
                [
                    'user:id,first_name,account_number',
                    'currency:id,name'
                ],
            )
                ->orderBy(
                    'created_at',
                    'desc',
                )
                ->get();
            return $this->successResponse(
                $plansInvoices,
            );
        } catch (\Exception $e) {
            return $this->failureResponse(
                'حدث خطأ أثناء جلب بيانات الفواتير: ' . $e->getMessage(),
            );
        }
    }

    public function patch(
        Request $request,
    ) {
        $request->validate(
            [
                'status' => 'required|in:accepted,rejected',
            ],
        );

        try {
            DB::beginTransaction();
            $planInvoice = PlanInvoice::with(
                [
                    'user:id,first_name,account_number',
                    'currency:id,name',
                ],
            )
                ->find(
                    $request->id,
                );
            if (!$planInvoice) {
                return $this->failureResponse('الفاتورة غير موجودة.');
            }
            $planInvoice->status = $request->status;
            if ($request->status === 'accepted') {
                $user = User::find($planInvoice->user_id);
                if (!$user) {
                    throw new \Exception('المستخدم المرتبط غير موجود.');
                }
                if ($user->userPlan) {
                    $user->userPlan->update(
                        [
                            'plan_id' => $planInvoice->plan_id,
                        ],
                    );
                } else {
                    $user->userPlan()->create(
                        [
                            'plan_id' => $planInvoice->plan_id,
                        ],
                    );
                }
            }

            $planInvoice->save();
            if ($planInvoice->user) {
                $planInvoice->user->makeHidden(['id']);
            }
            if ($planInvoice->currency) {
                $planInvoice->currency->makeHidden(['id']);
            }
            DB::commit();
            return $this->successResponse($planInvoice);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failureResponse('حدث خطأ: ' . $e->getMessage());
        }
    }
}
