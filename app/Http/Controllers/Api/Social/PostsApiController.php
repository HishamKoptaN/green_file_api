<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Models\Social\Post\Post;
use App\Helpers\uploadImageHelper;

class PostsApiController extends Controller
{
    public function handleReq(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                if ($id) {
                    return $this->getCmnts(
                        $request,
                        $id,
                    );
                } else {
                    return $this->get(
                        $request,
                    );
                }
            case 'POST':
                if ($id) {
                    return $this->sharePost(
                        $request,
                        $id,
                    );
                } else {
                    return $this->create(
                        $request,
                    );
                }
            case 'PUT':
                return $this->toggleLike(
                    $id,
                );
            default:
                return $this->failureRes();
        }
    }
    public function get(Request $request)
    {
        try {
            $query = Post::with(['user.userable']);

            //! إذا تم إرسال my_data=true، يتم جلب منشورات المستخدم المسجل دخول فقط
            if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                $query->where('user_id', Auth::id());
            } else {
                //! إذا لم يتم إرسال my_data، يمكن استخدام user_id إن وجد
                $query->when($request->user_id, function ($q, $userId) {
                    return $q->where('user_id', $userId);
                });
            }

            //! فلترة بناءً على النوع (type) إذا تم إرساله
            $query->when($request->type, function ($q, $type) {
                return $q->where('type', $type);
            });

            //! جلب البيانات مع الترتيب الأحدث والتقسيم إلى صفحات
            $posts = $query->latest()->paginate(4);

            return successRes(paginateRes($posts, PostResource::class, 'posts'));

        } catch (\Exception $e) {
            return failureRes($e->getMessage());
        }
    }




    public function create(
        Request $request,
    ) {
        try {
            $user = auth()->user();
            $imagePath = $request->hasFile('image')
                ? uploadImageHelper::uploadImage(
                    $request,
                    $user,
                    'posts',
                    'image',
                )
                : null;
            $post = Post::create(
                [
                    'user_id' => auth()->id(),
                    'content' => $request->content,
                    'image' => $imagePath,
                    'original_post_id' => $request->original_post_id,
                ],
            );
            return successRes(
                new PostResource(
                    $post->fresh(),
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                $e->getMessage(),
            );
        }
    }
    public function destroy(
        $id,
    ) {
        $post = Post::find($id);
    }
}
