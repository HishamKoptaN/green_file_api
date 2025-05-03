<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Status\UserStatusResource;
use App\Http\Resources\Social\Status\StatusResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Status\Status;
use App\Helpers\uploadImageHelper;
use App\Models\User\User;

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
            $userStatuses = User::with(
                [
                    'statuses' => function ($query) {
                        $query->latest()->limit(15);
                    },
                ],
            )->has('statuses')->where('id', '!=', auth()->id())->paginate(10);
            return successRes(
                paginateRes(
                    $userStatuses,
                    UserStatusResource::class,
                    'user_statuses'
                )
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function userStatuses()
    {
        try {
            $user = auth()->user();
            $userStatuses = $user->statuses()->latest()->get();
            return successRes(
                StatusResource::collection($userStatuses)
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
                return response()->json(['error' => 'Unauthorized'], 401);
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
                    user:  $user,
                    folder:  'statuses',
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
    private function handleUpload(
        $request,
        $user,
        $folder,
        $field,
        $fallback = null,
    ) {
        return $request->hasFile($field)
            ? UploadImageHelper::uploadFile(
                $request,
                $user,
                $folder,
                $field,
            )
            : ($fallback ?? null
            );
    }
}
