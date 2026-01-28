<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TimelineQuery
{
    public function __construct(private readonly Profile $viewer) {}

    public static function forViewer(Profile $viewer): self
    {
        return new self($viewer);
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
        $followedProfileIds = $this->viewer->followings()->pluck('following_profile_id')->prepend($this->viewer->id);

        return Post::query()
            ->whereIn('profile_id', $followedProfileIds)
            ->whereNull('parent_id')
            ->with([
                'profile',
                'repostOf' => fn ($q) => $q->withCount(['likes', 'replies', 'reposts'])->with('profile'),
            ])
            ->withCount(['likes', 'replies', 'reposts'])
            ->withExists([
                'likes as liked_by_viewer' => fn ($q) => $q->where('profile_id', $this->viewer->id),
                'reposts as reposted_by_viewer' => fn ($q) => $q->where('profile_id', $this->viewer->id),
                'repostOf as liked_original_post_by_viewer' => fn ($q) => $q
                    ->whereHas('likes', fn ($q) => $q->where('profile_id', $this->viewer->id)),
                'repostOf as reposted_original_post_by_viewer' => fn ($q) => $q
                    ->whereHas('reposts', fn ($q) => $q->where('profile_id', $this->viewer->id)),
            ])
            ->latest();
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
