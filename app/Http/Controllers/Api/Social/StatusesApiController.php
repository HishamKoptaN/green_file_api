<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Status\UserStatusResource;
use App\Http\Resources\Social\Status\StatusResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Status\StatusView;
use App\Models\Social\Status\Status;
use App\Models\Social\Status\StatusLike;
use App\Models\Social\Status\StatusReport;
use App\Models\Social\Status\HiddenStatus;
use App\Models\Social\Status\StatusMessage;
use App\Helpers\uploadImageHelper;
use App\Models\User\User;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\Social\Status\ViewerResource;

class StatusesApiController extends Controller
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
            $authUser = Auth::guard('sanctum')->user();
            // الحالات التي أخفاها المستخدم
            $hiddenStatusIds = HiddenStatus::where('user_id', $authUser->id)->pluck('status_id')->toArray();
            $users = User::with([
                'statuses' => function ($query) use ($hiddenStatusIds) {
                    $query->latest()
                        ->limit(15)
                        ->whereNotIn('id', $hiddenStatusIds); // استبعاد الحالات المخفية
                },
            ])
                ->where('id', '!=', $authUser->id)
                ->whereHas('statuses', function ($query) use ($hiddenStatusIds) {
                    $query->whereNotIn('id', $hiddenStatusIds);
                })
                ->get()
                ->map(function ($user) use ($authUser) {
                    $unseenCount = $user->statuses->filter(function ($status) use ($authUser) {
                        return !$status->views->contains('user_id', $authUser->id);
                    })->count();

                    $user->unseen_statuses_count = $unseenCount;
                    return $user;
                })
                ->sortBy(function ($user) {
                    return $user->unseen_statuses_count === 0 ? 1 : 0;
                })
                ->values(); // Reset indexing

            $page = request()->get('page', 1);
            $perPage = 10;

            $paginated = new LengthAwarePaginator(
                $users->forPage($page, $perPage),
                $users->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return successRes(
                paginateRes(
                    $paginated,
                    UserStatusResource::class,
                    'user_statuses'
                )
            );
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }

    public function userStatuses()
    {
        try {
            $user = auth()->user();
            $userStatuses = $user->statuses()->latest()->get();
            return successRes(
                StatusResource::collection(
                    $userStatuses,
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function create(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(
                    [
                        'error' => 'Unauthorized',
                    ],
                    401,
                );
            }
            $imagePath = $request->hasFile('image')
                ?  uploadImageHelper::uploadFile(
                    request: $request,
                    user: $user,
                    folder: 'statuses',
                    fieldName: 'image',

                ) : null;
            $videoPath = $request->hasFile('video')
                ?  uploadImageHelper::uploadFile(
                    request: $request,
                    user: $user,
                    folder: 'statuses',
                    fieldName: 'video',
                ) : null;
            $audioPath = $request->hasFile('audio')
                ?  uploadImageHelper::uploadFile(
                    request: $request,
                    user: $user,
                    folder: 'statuses',
                    fieldName: 'audio',
                ) : null;
            $status = Status::create(
                [
                    'user_id' => $user->id,
                    'content' => $request->filled('content') ? $request->content : null,
                    'image' => $imagePath,
                    'video' => $videoPath,
                    'audio' => $audioPath,
                    'font_family' => $request->filled('font_family') ? $request->font_family : null,
                    'font_size' => $request->filled('font_size') ? $request->font_size : null,
                    'font_color' => $request->filled('font_color') ? $request->font_color : null,
                ],
            );
            return successRes(
                new StatusResource(
                    $status->fresh(),
                ),
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function viewStatus(
        $status_id,
    ) {
        $user = Auth::guard(
            'sanctum',
        )->user();
        if (!$user) {
            return response()->json(
                [
                    'message' => 'Unauthorized',
                ],
                401,
            );
        }
        $status = Status::find(
            $status_id,
        );
        if (!$status) {
            return response()->json(
                [
                    'message' => 'Status not found',
                ],
                404,
            );
        }
        $alreadyViewed = StatusView::where(
            'status_id',
            $status_id,
        )
            ->where(
                'user_id',
                $user->id,
            )
            ->first();
        if (!$alreadyViewed) {
            StatusView::create(
                [
                    'status_id' => $status_id,
                    'user_id' => $user->id,
                    'viewed_at' => now(),
                ],
            );
        }
        return successRes(
            null,
            201,
        );
    }
    public function statusViewers($statusId)
    {
        try {
            $status = Status::findOrFail($statusId);
            $viewers = $status->viewers()->with('user')->get();

            // نستخدم map لتمرير الـ status لكل Resource
            $viewerResources = $viewers->map(function ($viewer) use ($status) {
                return new ViewerResource($viewer, $status);
            });

            return successRes($viewerResources);
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }

    public function hideStatus($statusId)
    {
        try {
            $user = auth()->user();

            HiddenStatus::firstOrCreate([
                'user_id' => $user->id,
                'status_id' => $statusId,
            ]);
            return successRes();
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }
    public function reportStatus(Request $request, $statusId)
    {
        try {
            $user = auth()->user();
            $reason = $request->getContent();
            StatusReport::create([
                'user_id' => $user->id,
                'status_id' => $statusId,
                'reason' => $reason,
            ]);
            return successRes();
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }
    public function toggleLike($status)
    {
        $user = Auth::guard('sanctum')->user();

        $like = StatusLike::where('user_id', $user->id)
            ->where('status_id', $status)
            ->first();

        if ($like) {
            $like->delete();
            return successRes();
        }

        StatusLike::create([
            'user_id' => $user->id,
            'status_id' => $status,
        ]);

        return successRes();
    }

    public function msg(Request $request, Status $status)
    {
        $user = Auth::guard('sanctum')->user();

        // تأكد من صحة الرسالة
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // إنشاء الرسالة
        $status->comments()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return successRes();
    }

    public function destroy(Status $status)
    {
        $authUser = Auth::guard('sanctum')->user();

        if ($status->user_id !== $authUser->id) {
            return failureRes('غير مصرح لك بحذف هذه الحالة', 403);
        }

        $status->delete();
        return successRes();
    }
}
