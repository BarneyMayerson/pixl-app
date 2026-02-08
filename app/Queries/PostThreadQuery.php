<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\Profile;

class PostThreadQuery
{
    public function __construct(private readonly Post $post, private readonly ?Profile $viewer) {}

    public static function for(Post $post, ?Profile $viewer): self
    {
        return new self($post, $viewer);
    }

    public function load(): Post
    {
        $viewverId = $this->viewer?->id ?? 0;

        $this->post
            ->load([
                'profile',
                'replies' => fn ($q) => $q
                    ->withCount(['likes', 'replies', 'reposts'])
                    ->withExists([
                        'likes as liked_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
                        'reposts as reposted_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
                    ])
                    ->with([
                        'profile',
                        'parent.profile',
                        'replies' => fn ($q) => $q
                            ->withCount(['likes', 'replies', 'reposts'])
                            ->withExists([
                                'likes as liked_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
                                'reposts as reposted_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
                            ])
                            ->with(['profile', 'parent.profile'])
                            ->oldest(),
                    ])
                    ->oldest(),
            ])
            ->loadCount(['likes', 'replies', 'reposts'])
            ->loadExists([
                'likes as liked_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
                'reposts as reposted_by_viewer' => fn ($q) => $q->where('profile_id', $viewverId),
            ]);

        return $this->post;
    }
}
