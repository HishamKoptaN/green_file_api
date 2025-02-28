<?php

namespace App\Http\Controllers\Api\BusinessFile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessFile\CompanyPostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BusinessFile\CompanyPost;
class CompanyPostsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->create(
                    $request,
                );
            default:
                return failureRes();

                
        }
    }
    public function get()
    {
        try {
            $companyPosts = CompanyPost::with('company')->paginate(10);
            return successRes(
                paginateRes(
                    $companyPosts,
                    CompanyPostResource::class,
                    'companyPosts',
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
    )
    {
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
            $new = CompanyPost::create(
                [
                    'post_id' => $request->id,
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                ],
            );
            return successRes(
                new CompanyPostResource(
                    $new->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
}
