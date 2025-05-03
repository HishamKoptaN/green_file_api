<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Social\Post\PostResource;
use App\Http\Resources\Social\Post\DraftResource;
use App\Helpers\uploadImageHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Social\Post\Draft;
use App\Models\Social\Post\Post;

class PostsApiController extends Controller
{
    public function get(
        Request $request,
    ) {
        try {
            $query = Post::with(
                [
                    'postable',
                    'user.userable',
                ],
            );
            //! فلترة حسب الجدولة
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
                //! فلترة حسب "my_data"
                if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->when(
                        $request->user_id,
                        function ($q, $userId) {
                            return $q->where('user_id', $userId);
                        },
                    );
                }
                //! فلترة حسب النوع (type)
                $query->when(
                    $request->type,
                    function ($q, $type) {
                        $types = is_array($type) ? $type : explode(',', $type);
                        $modelTypes = collect($types)->map(
                            function ($t) {
                                return match ($t) {
                                    'socialPost' => \App\Models\Social\Post\SocialPost::class,
                                    'sharedPost' => \App\Models\Social\Post\SharedPost::class,
                                    'companyPost' => \App\Models\Social\Post\CompanyPost::class,
                                    'news' => \App\Models\BusinessFile\News::class,
                                    'poll' => \App\Models\Social\Post\Poll::class,
                                    'occasion' => \App\Models\Social\Post\Occasion::class,
                                    default => null,
                                };
                            },
                        )->filter()->values()->toArray();
                        if (!empty($modelTypes)) {
                            $q->whereIn('postable_type', $modelTypes);
                        }
                        return $q;
                    },
                );
            }
            //! جلب البيانات مع الترتيب الأحدث والتقسيم إلى صفحات
            $posts = $query->latest()->paginate(5);
            //! جلب المنشور المؤقت
            $draftPost = Draft::where('user_id', Auth::id())
                ->first();
            //! تحميل العلاقات الإضافية للـ Poll فقط
            $posts->getCollection()->each(
                function ($post) {
                    // إذا كان المنشور من نوع Poll، قم بتحميل الخيارات
                    if ($post->postable instanceof \App\Models\Social\Post\Poll) {
                        $post->postable->load('options');
                    }
                    //! إذا كان المنشور من نوع sharedPost، قم بتحميل المنشور الأصلي (postable)
                    if ($post->postable instanceof \App\Models\Social\Post\SharedPost && $post->postable->postable) {
                        $post->originalPost = new PostResource($post->postable->postable);
                    }
                },
            );
            //! إرسال الاستجابة
            return successRes(
                [
                    'posts' => paginateRes($posts, PostResource::class, 'posts'),
                    'draft_post' => $draftPost ? new DraftResource($draftPost) : null,
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
            if (!$user) {
                return failureRes("المستخدم غير مسجل الدخول");
            }
            $postType = $request->input('type', 'socialPost');
            // رفع الصور والملفات إن وجدت
            $imagePath = $this->handleUpload($request, $user, 'posts', 'image', $request->image_url);
            $pdfPath = $this->handleUpload($request, $user, 'pdfs', 'pdf', $request->pdf_url);
            $videoPath = $this->handleUpload($request, $user, 'videos', 'video', $request->video_url);
            // تحديد وقت النشر
            $publishAt = match ($postType) {
                'scheduled' => $request->input('publish_at'),
                default => now(),
            };
            // إنشاء المنشور داخل transaction
            $post = DB::transaction(
                function () use (
                    $request,
                    $user,
                    $postType,
                    $publishAt,
                    $imagePath,
                    $pdfPath,
                    $videoPath,
                ) {
                    $post = Post::create(
                        [
                            'user_id' => $user->id,
                            'type' => $postType,
                            'publish_at' => $postType === 'scheduling'
                                ? $request->input('publish_at')
                                : now(),
                        ],
                    );
                    $postable = match ($postType) {
                        'socialPost' => $this->createSocialPost($request, $imagePath, $pdfPath, $videoPath),
                        'occasion'   => $this->createOccasion($request),
                        'poll'       => $this->createPoll($request),
                        'draft'      => $this->createDraft($request, $imagePath, $pdfPath, $videoPath),
                        'scheduling'  => $this->createSocialPost($request, $imagePath, $pdfPath, $videoPath),
                        default      => null,
                    };
                    if ($postable) {
                        $post->postable()->associate($postable);
                        $post->save();
                    }
                    return $post;
                },
            );
            $post->load(
                [
                    'user.userable',
                    'postable',
                ],
            );
            if ($post->postable instanceof \App\Models\Social\Post\Poll) {
                $post->postable->load(
                    'options',
                );
            }
            return successRes(
                new PostResource(
                    $post,
                ),
                201,
            );
        } catch (\Exception $e) {
            return failureRes(
                "حدث خطأ أثناء إنشاء المنشور: " . $e->getMessage(),
            );
        }
    }
    private function createSocialPost(
        Request $request,
        $image,
        $pdf,
        $video,
    ) {
        return \App\Models\Social\Post\SocialPost::create(
            [
                'content' => $request->input(
                    'content',
                ),
                'image' => $image,
                'video' => $video,
                'pdf' => $pdf,
            ],
        );
    }

    private function createOccasion(
        Request $request,
    ) {
        $event = $request->input('event');
        return \App\Models\Social\Post\Occasion::create(
            [
                'title' => $event['title'] ?? '',
                'description' => $event['description'] ?? '',
                'link' => $event['link'] ?? '',
                'start_at' => $event['start_at'] ?? now(),
                'end_at' => $event['end_at'] ?? null,
                'image' => $request->file('event.image') ? UploadImageHelper::uploadFile($request, Auth::user(), 'posts', 'event.image') : null,
            ],
        );
    }

    private function createPoll(Request $request)
    {
        $pollData = $request->input('poll');
        $poll = \App\Models\Social\Post\Poll::create(
            [
                'end_at' => now()->addDays((int) ($pollData['period'] ?? 3)),
            ],
        );
        foreach ($pollData['options'] ?? [] as $option) {
            $poll->options()->create(['option' => $option]);
        }
        return $poll;
    }
    public function createDraft(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return failureRes("المستخدم غير مسجل الدخول");
            }
            // تحديد مصدر الملفات: من رابط أو من ملف مرفوع
            $imagePath = $request->hasFile('image')
                ? $this->handleUpload($request, $user, 'posts', 'image', $request->file('image'))
                : $request->input('image_url');

            $pdfPath = $request->hasFile('pdf')
                ? $this->handleUpload($request, $user, 'pdfs', 'pdf', $request->file('pdf'))
                : $request->input('pdf_url');

            $videoPath = $request->hasFile('video')
                ? $this->handleUpload($request, $user, 'videos', 'video', $request->file('video'))
                : $request->input('video_url');

            // إنشاء أو تحديث المسودة
            $draft = \App\Models\Social\Post\Draft::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'content' => $request->input('content'),
                    'image' => $imagePath,
                    'video' => $videoPath,
                    'pdf' => $pdfPath,
                ]
            );
            return successRes(
                new \App\Http\Resources\Social\Post\DraftResource($draft),
                200,
            );
        } catch (\Exception $e) {
            return failureRes("حدث خطأ أثناء حفظ المسودة: " . $e->getMessage());
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
// $draft = ($request->input('type') === 'draft');
// $postType = $request->input('type', 'socialPost');
// $post = DB::transaction(
//     function () use ($request, $user, $draft, $finalPublishAt, $imagePath, $pdfPath, $postType) {
//         $post = $draft
//             ? Post::where('user_id', $user->id)->whereNull('publish_at')->first()
//             : null;
//         if ($post) {
//             $post->update([
//                 'content'    => $request->content,
//                 'image'      => $imagePath,
//                 'pdf'        => $pdfPath,
//                 'publish_at' => $finalPublishAt,
//             ]);
//         } else {
//         }
//         if ($postType === 'event') {
//             $this->handleEvent($request, $post, $user);
//         } elseif ($postType === 'poll') {
//             $this->handlePoll($request, $post);
//         }
//         return $post;
//     },
// );
//

// if ($postType === 'draft') {
//     return $this->createDraft($request); // <-- استدعاء مباشر
// }
