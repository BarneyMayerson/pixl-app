<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at' => $this->created_at,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'repost_of' => new PostResource($this->whenLoaded('repostOf')),
            'replies' => PostResource::collection($this->whenLoaded('replies')),
            'reposts' => PostResource::collection($this->whenLoaded('reposts')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
        ];
    }
}
