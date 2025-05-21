<?php

namespace App\Http\Resources\Social\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Company;
use App\Models\Social\Post\Comment;
use App\Models\Social\Post\Post;
use App\Models\Social\Post\Poll;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    public function toArray(
        $request,
    ) {
        $postArray = [
            'id' => $this->id,
            'type' => $this->postable_type,
            'post_owner' => $this->getPostOwnerDetails($this->user),
            'likes_count' => $this->likes()->count(),
            'is_like' => $this->isLikedByUser(),
            'shares_count' => $this->shares()->count(),
            'postable' => new PostableResource(
                $this->whenLoaded(
                    'postable',
                ),
            ),
            'cmnts_count' => Comment::where('commentable_id', $this->id)
                ->where('commentable_type', Post::class)
                ->count(),
            'cmnters_images' => Comment::where('commentable_id', $this->id)
                ->where('commentable_type', Post::class)
                ->with('user.userable') // تحميل العلاقة للوصول للصورة
                ->latest()
                ->take(10) // زوّد العدد قليلًا حتى تضمن تنوع المستخدمين
                ->get()
                ->map(function ($comment) {
                    $user = $comment->user;
                    return [
                        'id' => $user->id,
                        'image' => optional($user->userable)->image,
                    ];
                })
                ->unique('id')
                ->pluck('image')
                ->filter()
                ->values(),

            'created_at' => $this->created_at->diffForHumans(),
            'publish_at' => $this->publish_at->diffForHumans(),
        ];
        return $postArray;
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
            \App\Models\Social\Post\SocialPost::class => new CustomPostResource($this),
            \App\Models\Social\Post\SharedPost::class => new SharedPostResource($this),
            \App\Models\Social\Post\Occasion::class => new OccasionResource($this),
            \App\Models\Social\Post\Poll::class => new PollResource($this),
            \App\Models\Social\Post\Draft::class => new CustomPostResource($this),
            \App\Models\Social\Post\CompanyPost::class => new CustomPostResource($this),
            \App\Models\Social\Post\News::class => new CustomPostResource($this),
            \App\Models\Social\Post\ServiceRequest::class => new ServiceRequestResource($this),
            default => new CustomPostResource($this),
        };
    }
}

class SharedPostResource extends JsonResource
{
    public function toArray(
        $request,
    ) {

        $sharedPost = Post::with('postable')->find(
            $this->post_id,
        );
        if ($sharedPost->postable instanceof Poll) {
            $sharedPost->postable->load('options');
        }
        $postArray = [
            'id' => $this->id,
            'type' => $this->type,
            'post_owner' => $this->getPostOwnerDetails($this->user),
            'shared_post' => new PostResource(
                $sharedPost,
            ),
            'created_at' => $this->created_at->diffForHumans(),
            'publish_at' => $this->publish_at,
        ];
        return $postArray;
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
            'name' => $owner instanceof \App\Models\User\Company ? $owner->name : "{$owner->first_name} {$owner->last_name}",
            'image' => $owner->image,
            'is_following' => $isFollowing,
        ];
    }
}
class CustomPostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image' => $this->image,
            'video' => $this->video,
            'pdf' => $this->pdf,
            'end_at' => $this->end_at,
        ];
    }
}
class OccasionResource extends JsonResource
{
    public function toArray($request)
    {
        // الحصول على المستخدم المسجل
        $authUser = Auth::guard('sanctum')->user();
        // تأكد من أنك تمرر الكائن الفعلي (ليس Resource)
        $occasion = \App\Models\Social\Post\Occasion::find($this->id); // هنا نستخدم الكائن الفعلي الذي هو من نوع Occasion
        // التحقق إذا كان المستخدم مهتمًا بالـ Occasion
        $isInterested = $authUser->isInterestedIn($occasion);
        return [
            'id' => $occasion->id,
            'image' => $occasion->image,
            'title' => $occasion->title,
            'description' => $occasion->description,
            'interested_count' => $occasion->interested_count,
            'is_interested' => $isInterested,
            'start_at' => Carbon::parse($occasion->start_at)->locale('ar')->translatedFormat('d F, Y g:i A'),
            'end_at' => Carbon::parse($occasion->end_at)->locale('ar')->translatedFormat('d F, Y g:i A'),
        ];
    }
}

class PollResource extends JsonResource
{
    public function toArray(
        $request,
    ) {
        $user = Auth::user();
        $selectedOptionId = $this->options()
            ->whereHas('votes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->pluck('id')
            ->first();
       $this->when(
            $this->type === 'poll', // تحقق من نوع المنشور
            function () {
                return PollOptionResource::collection($this->whenLoaded('options'));
            },
            []
        );
        return [
            'id' => $this->id,
            'status' => $this->status,
            'question' => $this->question,
            'options' => PollOptionResource::collection(
                $this->whenLoaded(
                    'options',
                ),
            ),
            'selected_option' => $selectedOptionId,
            'end_at' => $this->end_at,
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
            'votes_count' => $this->votes,
        ];
    }
}
class ServiceRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'specialization_id' => $this->specialization_id,
            'details' => $this->details,
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
