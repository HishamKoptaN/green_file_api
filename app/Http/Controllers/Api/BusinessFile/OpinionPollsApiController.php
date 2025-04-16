<?php

namespace App\Http\Controllers\Api\BusinessFile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessFile\OpinionPolls\OpinionPollResource;
use Illuminate\Http\Request;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;
use Illuminate\Support\Facades\DB;

class OpinionPollsApiController extends Controller
{
    public function handleReq(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->create(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    public function get()
    {
        try {
            $opinionPolls = OpinionPoll::with(
                [
                    'company',
                    'options',
                ],
            )->paginate(
                10
            );
            return successRes(
                paginateRes(
                    $opinionPolls,
                    OpinionPollResource::class,
                    'opinionPolls',
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function create(Request $request)
    {
        $validated = $request->validate(
            [
                'content' => 'required|string',
                'options' => 'required|array|min:2',
                'options.*' => 'required|string',
            ],
        );
        DB::beginTransaction();
        try {
            $poll = OpinionPoll::create(
                [
                    'content' => $validated['content'],
                    'company_id' => $request->company_id ?? 12,
                    'end_date' => $validated['end_date'] ?? null,
                ],
            );
            foreach ($validated['options'] as $optionText) {
                $poll->options()->create(
                    [
                        'option' => $optionText,
                        'votes' => 0,
                    ],
                );
            }
            // تأكيد العملية في قاعدة البيانات
            DB::commit();
            return successRes(
                new OpinionPollResource($poll->fresh()),
                201
            );
        } catch (\Exception $e) {
            // التراجع عن العملية في حالة حدوث خطأ
            DB::rollBack();
            return failureRes($e->getMessage());
        }
    }
}
