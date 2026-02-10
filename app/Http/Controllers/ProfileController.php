<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Profile;
use App\Queries\ProfilePageQuery;
use App\Queries\ProfileWithRepliesQuery;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(Profile $profile)
    {
        $profile->loadCount(['followers', 'followings']);

        $profile->has_followed = Auth::user() ? Auth::user()->profile->isFollowing($profile) : false;

        $posts = ProfilePageQuery::for($profile, Auth::user()?->profile)->get();

        return Inertia::render('Profiles/Show', [
            'profile' => $profile->toResource(),
            'posts' => $posts->toResourceCollection(),
        ]);
    }

    public function replies(Profile $profile)
    {
        $profile->loadCount(['followers', 'followings']);

        $posts = ProfileWithRepliesQuery::for($profile, Auth::user()?->profile)->get();

        return Inertia::render('Profiles/Show', [
            'profile' => $profile->toResource(),
            'posts' => $posts->toResourceCollection(),
        ]);
    }

    public function follow(Profile $profile)
    {
        $follower = Auth::user()->profile;

        if ($follower->is($profile)) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        Follow::createFollow($follower, $profile);

        return back()->with('success', "You are now following this profile [{$profile->handle}].");
    }

    public function unfollow(Profile $profile)
    {
        $follower = Auth::user()->profile;

        if ($follower->is($profile)) {
            return back()->with('error', 'You cannot unfollow yourself.');
        }

        Follow::removeFollow($follower, $profile);

        return back()->with('success', "You are no longer following this profile [{$profile->handle}].");
    }
}
