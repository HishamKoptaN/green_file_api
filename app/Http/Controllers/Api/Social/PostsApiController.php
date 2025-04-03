<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Models\Social\Post\Post;
use App\Helpers\uploadImageHelper;
use App\Events\NotificationSent;

class PostsApiController extends Controller
{
    public function get(
        Request $request,
    ) {
        try {
            $query = Post::with(
                [
                    'user.userable',
                ],
            )->where(
                function ($q) {
                    $q->whereNull('publish_at')
                        ->orWhere('publish_at', '<=', now());
                },
            );
            //! إذا تم إرسال my_data=true، يتم جلب منشورات المستخدم المسجل دخول فقط
            if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                $query->where('user_id', Auth::id());
            } else {
                //! إذا لم يتم إرسال my_data، يمكن استخدام user_id إن وجد
                $query->when(
                    $request->user_id,
                    function ($q, $userId) {
                        return $q->where('user_id', $userId);
                    },
                );
            }
            //! فلترة بناءً على النوع (type) إذا تم إرساله
            $query->when(
                $request->type,
                function ($q, $type) {
                    return $q->where('type', $type);
                },
            );
            //! جلب البيانات مع الترتيب الأحدث والتقسيم إلى صفحات
            $posts = $query->latest()->paginate(4);
            return successRes(
                paginateRes(
                    $posts,
                    PostResource::class,
                    'posts',
                ),
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
            $user = Auth::user();
            $imagePath = $request->hasFile('image')
                ? UploadImageHelper::uploadImage(
                    $request,
                    $user,
                    'posts',
                    'image',
                )
                : null;

            $post = Post::create(
                [
                    'user_id' => $user->id,
                    'content' => $request->content,
                    'image' => $imagePath,
                    'publish_at' => $request->publish_at,
                ],
            );
            return successRes(
                new PostResource(
                    $post->fresh(),
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                "حدث خطأ أثناء إنشاء المنشور: " . $e->getMessage(),
            );
        }
    }
    public function sharePost(
        Request $request,
    ) {
        try {
            $user = Auth::user();
            $originalPost = Post::where(
                'id',
                $request->id,
            )->first();
            $post = Post::create(
                [
                    'user_id' => $user->id,
                    'content' => $request->content,
                    'original_post_id' => $request->id,
                    'publish_at' => $request->publish_at ?? now(),
                ],
            );
            //! إرسال إشعار لصاحب المنشور الأصلي
            $userName = $user->userable ? ($user->userable->first_name ?? $user->name) : $user->name;
            $image = $user->userable ? $user->userable->image : null;
            event(
                new NotificationSent(
                    userId: $originalPost->user_id,
                    title: 'إعادة مشاركة',
                    body: "$userName قام بإعادة مشاركة منشورك!",
                    image: $image,
                    data: []
                ),
            );
            return successRes(
                new PostResource(
                    $post->fresh(),
                ),
            );
        } catch (\Exception $e) {
            return failureRes(
                "خطأ أثناء مشاركة المنشور: " . $e->getMessage(),
            );
        }
    }

    public function destroy(
        $id,
    ) {
        $post = Post::find($id);
    }
}
