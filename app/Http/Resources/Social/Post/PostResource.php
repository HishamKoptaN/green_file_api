<?php
namespace App\Http\Resources\Social\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Company;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,  // المعرف
            'type' => $this->getPostType(),  // نوع المنشور
            'post_owner' => $this->getPostOwnerDetails($this->user),  // تفاصيل مالك المنشور
            'original_post' => $this->getOriginalPostDetails(),  // تفاصيل المنشور الأصلي (إن وجد)
            'likes_count' => $this->likes()->count(),  // عدد الإعجابات
            'isLike' => $this->isLikedByUser(),  // هل أعجب المستخدم بالمنشور؟
            'comments_count' => $this->comments()->count(),  // عدد التعليقات
            'shares_count' => $this->shares()->count(),  // عدد المشاركات
            'postable' => new PostableResource($this->whenLoaded('postable')),  // بيانات النوع المرتبط (مثل Poll أو Event)
            'created_at' => $this->created_at->diffForHumans(),  // تاريخ الإنشاء
            'publish_at' => $this->publish_at,  // تاريخ النشر
        ];
    }
    private function getPostType()
    {
        return match (get_class($this->postable)) {
            \App\Models\Social\Post\Event::class => 'event',
            \App\Models\Social\Post\Poll::class => 'poll',
            \App\Models\Social\Post\SocialPost::class => 'socialPost',
            \App\Models\Social\Post\CompanyPost::class => 'companyPost',
            \App\Models\Social\Post\News::class => 'news',
            \App\Models\Social\Post\Draft::class => 'draft',
            \App\Models\Social\Post\SharedPost::class => 'sharedPost',
            default => 'socialPost',
        };
    }
    private function getPostOwnerDetails($user)
    {
        $authUser = Auth::guard('sanctum')->user();

        if (!$user || !$user->userable) {
            return [
                'id' => null,
                'type' => 'Unknown',
                'name' => 'Anonymous',
                'image' => null,
                'is_following' => false,
            ];
        }

        $owner = $user->userable;
        $isFollowing = $authUser && $authUser->id !== $user->id
            ? $authUser->following()->where('followed_id', $user->id)->exists()
            : null;

        return [
            'id' => $owner->id,
            'type' => $owner->getMorphClass(),
            'name' => $owner instanceof Company ? $owner->name : "{$owner->first_name} {$owner->last_name}",
            'image' => $owner->image,
            'is_following' => $isFollowing,
        ];
    }

    // دالة للحصول على تفاصيل المنشور الأصلي (إذا كان موجودًا)
    private function getOriginalPostDetails()
    {
        if (!$this->original_post_id || !$this->originalPost) {
            return null;
        }

        $originalPost = $this->originalPost;

        return [
            'id' => $originalPost->id,
            'content' => $originalPost->content,
            'image' => $originalPost->image,
            'video' => $originalPost->video,
            'created_at' => $originalPost->created_at->diffForHumans(),
            'post_owner' => $this->getPostOwnerDetails($originalPost->user),
        ];
    }

    // دالة للتحقق إذا كان المستخدم قد أعجب بالمنشور
    private function isLikedByUser()
    {
        $user = Auth::guard('sanctum')->user();
        return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }
}

class PostableResource extends JsonResource
{
    public function toArray($request)
    {
        return match (get_class($this->resource)) {
            \App\Models\Social\Post\Event::class => new EventResource($this),
            \App\Models\Social\Post\Poll::class => new PollResource($this),
            \App\Models\Social\Post\Poll::class => new PollResource($this),
            \App\Models\Social\Post\Poll::class => new PollResource($this),
            \App\Models\Social\Post\Poll::class => new PollResource($this),
            default => null,
        };
    }
}

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'description' => $this->description,
            'end_at' => $this->end_at,
        ];
    }
}

class PollResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'question' => $this->question,
            'options' => PollOptionResource::collection(
                $this->whenLoaded(
                    'options',
                ),
            ),
            'end_date' => $this->end_date,
        ];
    }
}

class PollOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option' => $this->option,
            'votes_count' => $this->votes_count,
        ];
    }
}

class UserDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => $this->resource->getMorphClass(),
            'name' => $this->name,
            'image' => $this->image,
            'cover_image' => $this->cover_image,
        ];
    }
}
