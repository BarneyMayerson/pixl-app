<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProfilePageQuery
{
    public function __construct(private readonly Profile $subject, private readonly ?Profile $viewer) {}

    public static function for(Profile $subject, ?Profile $viewer): self
    {
        return new self($subject, $viewer);
    }

    public function get(): Collection
    {
        return $this->baseQuery()
            ->get()
            ->map(fn (Post $post) => $this->normalize($post));
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->paginate($perPage)
            ->through(fn (Post $post) => $this->normalize($post));
    }

    protected function baseQuery(): Builder
    {
        $viewerId = $this->viewer?->id ?? 0;

        $posts = Post::where('profile_id', $this->subject->id)
            ->whereNull('parent_id')
            ->with([
                'profile',
                'repostOf' => fn ($q) => $q
                    ->withCount(['likes', 'reposts', 'replies'])
                    ->with('profile'),
            ])
            ->withCount(['likes', 'reposts', 'replies'])
            ->withExists([
                'likes as liked_by_viewer' => fn ($q) => $q->where('profile_id', $viewerId),
                'reposts as reposted_by_viewer' => fn ($q) => $q->where('profile_id', $viewerId),
                'repostOf as liked_original_post_by_viewer' => fn ($q) => $q
                    ->whereHas('likes', fn ($q) => $q->where('profile_id', $viewerId)),
                'repostOf as reposted_original_post_by_viewer' => fn ($q) => $q
                    ->whereHas('reposts', fn ($q) => $q->where('profile_id', $viewerId)),
            ])
            ->latest();

        return $posts;
    }

    protected function normalize(Post $post): Post
    {
        if ($post->isRepost() && is_null($post->content)) {
            $post->repostOf->liked_by_viewer = (bool) $post->liked_original_post_by_viewer;
            $post->repostOf->reposted_by_viewer = (bool) $post->reposted_original_post_by_viewer;
        }

        return $post;
    }
}
