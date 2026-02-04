<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'display_name' => $this->display_name,
            'avatar_url' => $this->avatar_url,
            'handle' => $this->handle,
            'bio' => $this->bio,
            'followers_count' => $this->whenCounted('followers'),
            'following_count' => $this->whenCounted('following'),
            'posts_count' => $this->whenCounted('posts'),
            'replies_count' => $this->whenCounted('replies'),
            'reposts_count' => $this->whenCounted('reposts'),
            'likes_count' => $this->whenCounted('likes'),
        ];
    }
}
