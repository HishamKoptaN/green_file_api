<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\CustomCollection;
use App\Http\Resources\CustomResource;
use Illuminate\Http\Request;
use App\Models\Social\Post\Post;
use App\Models\Custom;

class CustomApiController extends Controller
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
            $jobs = Custom::with('cistom')->paginate(10);;
            return successRes(
                new CustomCollection(
                    $jobs,
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function create(Request $request)
    {
        try {
            $custom = Custom::create(
                [
                    'custom' => $request->custom,
                ],
            );
            return successRes(
                new CustomResource(
                    $custom->fresh(),
                ),
                201
            );
        } catch (\Exception $e) {
            return $this->failureRes(
                $e->getMessage(),
            );
        }
    }

    public function update(
        Request $request,
        $id,
    ) {
        $custom = Custom::find($id);
        $custom->update(
            [
                'custom' => $request->custom,
            ],
        );
    }
    public function destroy(
        $id,
    ) {
        $custom = Post::find($id);
    }
}
