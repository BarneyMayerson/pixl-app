<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Profile $profile)
    {
        $profile->loadCount(['followers', 'followings']);

        $posts = Post::where('profile_id', $profile->id)
            ->whereNull('parent_id')
            ->with(
                ['repostOf' => fn ($q) => $q->withCount(['likes', 'reposts', 'replies'])]
            )
            ->withCount(['likes', 'reposts', 'replies'])
            ->latest()
            ->get();

        return view('profiles.show', compact('profile', 'posts'));
    }

    public function replies(Profile $profile)
    {
        $profile->loadCount(['followers', 'followings']);

        $posts = Post::query()
            ->where(fn ($q) => $q
                ->whereBelongsTo($profile, 'profile')
                ->whereNull('parent_id')
            )
            ->orWhereHas('replies', fn ($q) => $q
                ->whereBelongsTo($profile, 'profile')
            )
            ->with([
                'profile',
                'repostOf' => fn ($q) => $q->withCount(['likes', 'reposts', 'replies']),
                'repostOf.profile',
                'parent.profile',
                'replies' => fn ($q) => $q
                    ->whereBelongsTo($profile, 'profile')
                    ->with('profile')
                    ->oldest(),
            ])
            ->withCount(['likes', 'reposts', 'replies'])
            ->latest()
            ->get();

        return view('profiles.replies', compact('profile', 'posts'));
    }

    public function follow(Profile $profile)
    {
        $follower = Auth::user()->profile;

        if ($follower->is($profile)) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        $follow = Follow::createFollow($follower, $profile);

        return response()->json(compact('follow'));
    }

    public function unfollow(Profile $profile)
    {
        $follower = Auth::user()->profile;

        if ($follower->is($profile)) {
            return back()->with('error', 'You cannot unfollow yourself.');
        }

        $result = Follow::removeFollow($follower, $profile);

        return response()->json(compact('result'));
    }
}
