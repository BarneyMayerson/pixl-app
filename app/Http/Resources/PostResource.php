<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at->diffForHumans(),
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'repost_of' => new PostResource($this->whenLoaded('repostOf')),
            'replies' => PostResource::collection($this->whenLoaded('replies')),
            'replies_count' => $this->whenCounted('replies'),
            'reposts' => PostResource::collection($this->whenLoaded('reposts')),
            'reposts_count' => $this->whenCounted('reposts'),
            'reposted_by_viewer' => $this->reposted_by_viewer,
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'likes_count' => $this->whenCounted('likes'),
            'liked_by_viewer' => $this->liked_by_viewer,
            'can' => [
                'update' => Auth::check() && Auth::user()->can('update', $this->resource),
            ],
        ];
    }
}
