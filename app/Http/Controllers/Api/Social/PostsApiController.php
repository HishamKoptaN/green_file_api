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
use App\Models\Social\Post\Occasion;
use App\Models\Social\Post\ServiceRequest;
use App\Models\Social\Post\SharedPost;
use App\Models\Social\Post\Poll;
use App\Models\Social\Post\SocialPost;
use Carbon\Carbon;

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
                    'comments.user',
                ],
            );
            // فلترة حسب الجدولة
            if (filter_var($request->scheduled, FILTER_VALIDATE_BOOLEAN)) {
                $query->where('user_id', Auth::id())
                    ->where('publish_at', '>', now());
            } else {
                $query->where(function ($q) {
                    $q->whereNull('publish_at')
                        ->orWhere('publish_at', '<=', now());
                });
                // فلترة حسب "my_data"
                if (filter_var($request->my_data, FILTER_VALIDATE_BOOLEAN)) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->when($request->user_id, function ($q, $userId) {
                        return $q->where('user_id', $userId);
                    });
                }
                // فلترة حسب النوع (type)
                if ($request->has('type')) {
                    $types = $request->type;  // يأخذ الأنواع من query
                    if (is_array($types)) {
                        $query->whereIn('postable_type', $types);
                    } else {
                        $query->where('postable_type', $types);
                    }
                }
            }
            // جلب البيانات مع الترتيب الأحدث والتقسيم إلى صفحات
            $posts = $query->orderByDesc('publish_at')->latest()->paginate(5);
            // جلب المنشور المؤقت
            $draftPost = Draft::where('user_id', Auth::id())->first();
            // تحميل العلاقات الإضافية للـ Poll فقط
            $posts->getCollection()->each(function ($post) {
                if ($post->postable instanceof \App\Models\Social\Post\Poll) {
                    $post->postable->load('options');
                }
                if ($post->postable instanceof \App\Models\Social\Post\SharedPost && $post->postable->postable) {
                    $post->originalPost = new PostResource($post->postable->postable);
                }
            });

            // إرسال الاستجابة
            return successRes([
                'posts' => paginateRes($posts, PostResource::class, 'posts'),
                'draft_post' => $draftPost ? new DraftResource($draftPost) : null,
            ]);
        } catch (\Exception $e) {
            return failureRes($e->getMessage());
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
            $postType = $request->input(
                'type',
                'socialPost',
            );
            //! رفع الصور والملفات إن وجدت
            $imageUpload = $this->handleUpload($request, $user, 'posts', 'image', $request->image_url);
            $pdfUpload = $this->handleUpload($request, $user, 'pdfs', 'pdf', $request->pdf_url);
            $videoUpload = $this->handleUpload($request, $user, 'videos', 'video', $request->video_url);
            $imagePath = $imageUpload['file_url'] ?? null;
            $pdfPath = $pdfUpload['file_url'] ?? null;
            $videoPath = $videoUpload['file_url'] ?? null;
            $thumbnail_url = $videoUpload['thumbnail_url'] ?? null;
            //! تحديد وقت النشر
            $publishAt = $request->filled('publish_at') ? $request->input('publish_at') : now();
            //! إنشاء المنشور داخل transaction
            if ($request->filled('publish_at') && Carbon::parse($request->publish_at)->isPast()) {
                return failureRes("لا يمكن تحديد وقت نشر في الماضي");
            }
            $post = DB::transaction(
                function () use (
                    $request,
                    $user,
                    $postType,
                    $publishAt,
                    $imagePath,
                    $pdfPath,
                    $videoPath,
                    $thumbnail_url,
                ) {
                    $post = Post::create(
                        [
                            'user_id' => $user->id,
                            'type' => $postType,
                            'publish_at' => $publishAt,

                        ],
                    );
                    $postable = match ($postType) {
                        'socialPost' => $this->createSocialPost(
                            $request,
                            $imagePath,
                            $pdfPath,
                            $videoPath,
                            $thumbnail_url,
                        ),
                        'sharedPost' => $this->createSharedPost(
                            $request,
                        ),
                        'occasion'   => $this->createOccasion(
                            $request,
                        ),
                        'poll'       => $this->createPoll(
                            $request,
                        ),
                        'draft'      => $this->createDraft(
                            $request,
                            $imagePath,
                            $pdfPath,
                            $videoPath,
                            $thumbnail_url,
                        ),
                        'scheduling'  => $this->createSocialPost(
                            $request,
                            $imagePath,
                            $pdfPath,
                            $videoPath,
                            $thumbnail_url,
                        ),
                        'servReq'  => $this->createSerReq(
                            $request,
                        ),
                        default      => null,
                    };
                    if ($postable) {
                        $post->postable()->associate(
                            $postable,
                        );
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
            if ($post->postable instanceof Poll) {
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
        $thumbnail_url,
    ) {
        return SocialPost::create(
            [
                'content' => $request->input(
                    'content',
                ),
                'image' => $image,
                'video' => $video,
                'thumbnail_url' => $thumbnail_url,
                'pdf' => $pdf,
            ],
        );
    }
    private function createSharedPost(
        Request $request,
    ) {
        return SharedPost::create(
            [
                'content' => $request->input(
                    'content',
                ),
                'post_id' => $request->input(
                    'post_id',
                ),
            ],
        );
    }

    private function createOccasion(
        Request $request,
    ) {
        $occasion = $request->input('occasion');
        return Occasion::create(
            [
                'title' => $occasion['title'] ?? '',
                'description' => $occasion['description'] ?? '',
                'link' => $occasion['link'] ?? '',
                'start_at' => $occasion['start_at'] ?? null,
                'end_at' => $occasion['end_at'] ?? null,
                'image' => $request->file('occasion.image') ? UploadImageHelper::uploadFile(
                    $request,
                    Auth::user(),
                    'posts',
                    'occasion.image',

                ) : null,
            ],
        );
    }
    private function createPoll(
        Request $request,
    ) {
        $pollData = $request->input('poll');
        $poll = Poll::create(
            [
                'question' => $request->input(
                    'content',
                ),
                'end_at' => now()->addDays(
                    (int) ($pollData['period'] ?? 3),
                ),
            ],
        );
        foreach ($pollData['options'] ?? [] as $option) {
            if (!empty($option)) {
                $poll->options()->create(['option' => $option]);
            }
        }
        return $poll;
    }
    //!createDraft
    public function createDraft(
        Request $request,
    ) {
        try {
            $user = Auth::user();
            if (!$user) {
                return failureRes("المستخدم غير مسجل الدخول");
            }
            $imageUpload= $request->hasFile('image')
                ? $this->handleUpload($request, $user, 'posts', 'image', $request->file('image'))
                : $request->input('image_url');
            $pdfUpload = $request->hasFile('pdf')
                ? $this->handleUpload($request, $user, 'pdfs', 'pdf', $request->file('pdf'))
                : $request->input('pdf_url');
            $videoUpload = $request->hasFile('video')
                ? $this->handleUpload($request, $user, 'videos', 'video', $request->file('video'))
                : $request->input('video_url');
            $imagePath = $imageUpload['file_url'] ?? null;
            $pdfPath = $pdfUpload['file_url'] ?? null;
            $videoPath = $videoUpload['file_url'] ?? null;
            $thumbnail_url = $videoUpload['thumbnail_url'] ?? null;
            $draft = Draft::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'content' => $request->input(
                        'content',
                    ),
                    'image' => $imagePath,
                    'video' => $videoPath,
                    'thumbnail_url' => $thumbnail_url,
                    'pdf' => $pdfPath,
                ]
            );
            return successRes(
                new DraftResource(
                    $draft,
                ),
                200,
            );
        } catch (\Exception $e) {
            return failureRes(
                "حدث خطأ أثناء حفظ المسودة: " . $e->getMessage(),
            );
        }
    }
    //! createSerReq
    public function createSerReq(
        Request $request,
    ) {
        $servReq = $request->input('serv_req');
        return ServiceRequest::create(
            [
                'title' =>  $servReq['title'] ?? '',
                'specialization_id' => $servReq['specialization_id'] ?? 1,
                'details' => $servReq['details'] ?? '',
            ],
        );
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
    public function delete(
        $id,
    ) {
        try {
            $post = Post::find(
                $id,
            );
            if (!$post) {
                return failureRes('المنشور غير موجود');
            }
            if ($post->user_id !== Auth::id()) {
                return failureRes('غير مصرح لك بحذف هذا المنشور');
            }
            if ($post->postable) {
                $post->postable->delete();
            }
            $post->delete();
            return successRes(
                null,
                204,
            );
        } catch (\Exception $e) {
            return failureRes(
                "حدث خطأ أثناء حذف المنشور: " . $e->getMessage(),
            );
        }
    }
}
