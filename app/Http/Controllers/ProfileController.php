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
