<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Models\Social\Post\Post;
use App\Helpers\uploadImageHelper;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\DB;
use App\Models\Social\Post\Event;
use App\Models\Social\Post\Draft;
use App\Models\Social\Post\Poll;
use App\Models\User\User;

class PostsApiController extends Controller
{
    public function get(
        Request $request,
    ) {
        try {
            $posts = Post::all(); // جلب جميع المنشورات

            return successRes(
                $posts
            );
            $query = Post::with(
                [
                    'user.userable',
                    'postable',
                ]
            );
            if (filter_var($request->scheduled, FILTER_VALIDATE_BOOLEAN)) {
                $query->where('user_id', Auth::id())
                    ->where('publish_at', '>', now());
            } else {
                $query->where(
                    function ($q) {
                        $q->whereNull('publish_at')
                            ->orWhere('publish_at', '<=', now());
                    },
                );
                if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->when(
                        $request->user_id,
                        function ($q, $userId) {
                            return $q->where('user_id', $userId);
                        }
                    );
                }
                // فلترة بناءً على النوع (type) إذا تم إرساله
                $query->when(
                    $request->type,
                    function ($q, $type) {
                        $types = is_array($type) ? $type : explode(',', $type);
                        $modelTypes = collect($types)->map(function ($t) {
                            return match ($t) {
                                'socialPost' => \App\Models\Social\Post\SocialPost::class,
                                'companyPost' => \App\Models\Social\Post\CompanyPost::class,
                                'news' => \App\Models\BusinessFile\News::class,
                                'poll' => \App\Models\Social\Post\Poll::class,
                                'event' => \App\Models\Social\Post\Event::class,
                                'draft' => \App\Models\Social\Post\Draft::class,
                                'sharedPost' => \App\Models\Social\Post\SharedPost::class,
                                default => null,
                            };
                        })->filter()->values()->toArray();
                        if (!empty($modelTypes)) {
                            $q->whereIn('postable_type', $modelTypes);
                        }
                        return $q;
                    },
                );
            }
            // جلب البيانات مع الترتيب الأحدث والتقسيم إلى صفحات
            $posts = $query->latest()->paginate(4);

            // جلب المنشور المؤقت
            $draftPost = Post::where('user_id', Auth::id())
                ->whereNull('publish_at')
                ->where('postable_type', Draft::class)
                ->latest()
                ->first();

            // تحميل علاقات إضافية للـ Poll فقط
            $posts->getCollection()->each(
                function ($post) {
                    if ($post->postable instanceof \App\Models\Social\Post\Poll) {
                        $post->postable->load('options');
                    }
                },
            );
            // إرسال الاستجابة
            return successRes(
                [
                    'posts' => paginateRes($posts, PostResource::class, 'posts'),
                    'draft_post' => $draftPost ? new PostResource($draftPost) : null,
                ],
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
            $draft = ($request->input('type') === 'draft');
            $finalPublishAt = $draft ? null : ($request->publish_at ?? now());

            $imagePath = $this->handleUpload($request, $user, 'posts', 'image', $request->image_url);
            $pdfPath = $this->handleUpload($request, $user, 'pdfs', 'pdf', $request->pdf_url);

            $postType = $request->input('type', 'socialPost');

            $post = DB::transaction(
                function () use ($request, $user, $draft, $finalPublishAt, $imagePath, $pdfPath, $postType) {
                    $post = $draft
                        ? Post::where('user_id', $user->id)->whereNull('publish_at')->first()
                        : null;

                    if ($post) {
                        $post->update(
                            [
                                'content' => $request->content,
                                'image' => $imagePath,
                                'pdf' => $pdfPath,
                                'publish_at' => $finalPublishAt,
                            ],
                        );
                    } else {
                        $post = Post::create(
                            [
                                'user_id' => $user->id,
                                'content' => $request->content,
                                'image' => $imagePath,
                                'pdf' => $pdfPath,
                                'publish_at' => $finalPublishAt,
                            ],
                        );
                    }

                    if ($postType === 'event') {
                        $this->handleEvent(
                            $request,
                            $post,
                            $user,
                        );
                    } elseif ($postType === 'poll') {
                        $this->handlePoll(
                            $request,
                            $post,
                        );
                    }

                    return $post;
                },
            );
            return successRes(new PostResource($post));
        } catch (\Exception $e) {
            return failureRes("حدث خطأ أثناء إنشاء المنشور: " . $e->getMessage());
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

    private function handleEvent(
        Request $request,
        Post $post,
        User $user,
    ) {
        $eventData = $request->input('event');
        if (!$eventData) {
            throw new \Exception('بيانات الحدث غير موجودة');
        }

        $image = $request->file('event.image');
        $imagePath = $image ? UploadImageHelper::uploadFile(
            $request,
            $user,
            'posts',
            'event.image',
        ) : null;
        $event = Event::create(
            [
                'image' => $imagePath,
                'title' => $eventData['title'] ?? '',
                'description' => $eventData['description'] ?? '',
                'link' => $eventData['link'] ?? '',
                'start_at' => $eventData['start_at'] ?? now(),
                'end_at' => $eventData['end_at'] ?? null,
            ],
        );
        //! ربط الحدث بالمنشور باستخدام العلاقة البوليمورفية
        $post->postable()->associate($event); // أو $post->event()->associate($event);
        $post->save();
    }
    private function handlePoll(
        Request $request,
        Post $post,
    ) {
        $pollData = $request->input('poll');
        if (!$pollData) return;
        $poll = Poll::create(
            [
                'question' => $pollData['question'] ?? null, // ✅
                'end_date' => now()->addDays($pollData['period'] ?? 3),
            ]
        );
        $post->postable()->associate($poll);
        $post->save();
        foreach ($pollData['options'] ?? [] as $option) {
            $poll->options()->create(
                [
                    'option' => $option,
                ],
            );
        }
    }

    public function sharePost(
        Request $request,
    ) {
        try {
            $user = Auth::user();
            $originalPost = Post::where('id', $request->id)->first();

            $post = Post::create(
                [
                    'user_id' => $user->id,
                    'content' => $request->content,
                    'original_post_id' => $request->id,
                    'publish_at' => $request->publish_at ?? now(),
                    'type' => $originalPost->type,  // إضافة نوع المنشور الأصلي
                ],
            );

            //! إرسال إشعار لصاحب المنشور الأصلي
            $userName = $user->userable ? ($user->userable->first_name ?? $user->name) : $user->name;
            $image = $user->userable ? $user->userable->image : null;

            if (!$user->sharedPosts->contains($originalPost->id)) {
                $user->sharedPosts()->attach($originalPost->id);
            }
            event(
                new NotificationSent(
                    userId: $originalPost->user_id,
                    title: 'إعادة مشاركة',
                    body: "$userName قام بإعادة مشاركة منشورك!",
                    image: $image,
                    data: []
                ),
            );
            return successRes(new PostResource($post->fresh()));
        } catch (\Exception $e) {
            return failureRes("خطأ أثناء مشاركة المنشور: " . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return failureRes('المنشور غير موجود');
            }
            // تحقق إن المستخدم هو صاحب المنشور (اختياري لكن جيد للأمان)
            if ($post->user_id !== Auth::id()) {
                return failureRes('غير مصرح لك بحذف هذا المنشور');
            }
            // حذف الكيان المرتبط بالمنشور إن وجد
            if ($post->postable) {
                $post->postable->delete();
            }
            // حذف المنشور نفسه
            $post->delete();
            return successRes('تم حذف المنشور بنجاح');
        } catch (\Exception $e) {
            return failureRes("حدث خطأ أثناء حذف المنشور: " . $e->getMessage());
        }
    }
}
