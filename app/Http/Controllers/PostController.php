<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;

        $posts = TimelineQuery::forViewer($profile)->get();

        return view('posts.index', compact('posts', 'profile'));
    }

    public function store(CreatePostRequest $request)
    {
        $profile = Auth::user()->profile;
        $post = Post::publish($profile, $request->validated()['content']);

        return to_route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Profile $profile, Post $post)
    {
        $post
            ->load([
                'replies' => fn ($q) => $q
                    ->withCount(['likes', 'replies', 'reposts'])
                    ->with([
                        'profile',
                        'parent.profile',
                        'replies' => fn ($q) => $q
                            ->withCount(['likes', 'replies', 'reposts'])
                            ->with(['profile', 'parent.profile'])
                            ->oldest(),
                    ])
                    ->oldest(),
            ])
            ->loadCount(['likes', 'replies', 'reposts']);

        return view('posts.show', compact('post'));
    }

    public function reply(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $replier = Auth::user()->profile;

        Post::reply($replier, $post, $request->content);

        return to_route('posts.index')->with('success', 'Reply posted.');
    }

    public function repost(Profile $profile, Post $post)
    {
        $reposter = Auth::user()->profile;

        Post::repost($reposter, $post);

        return to_route('posts.index')->with('success', 'Reposted.');
    }

    public function quote(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $replier = Auth::user()->profile;

        Post::quote($replier, $post, $request->content);

        return to_route('posts.index')->with('success', 'Quote posted.');
    }

    public function like(Profile $profile, Post $post)
    {
        $liker = Auth::user()->profile;

        $like = Like::createLike($liker, $post);

        return response()->json(compact('like'));
    }

    public function unlike(Profile $profile, Post $post)
    {
        $liker = Auth::user()->profile;

        $result = Like::removeLike($liker, $post);

        return response()->json(compact('result'));
    }

    public function destroy(Profile $profile, Post $post)
    {
        $currentProfile = Auth::user()->profile;

        if ($currentProfile->is($profile)) {
            $result = $post->delete();

            return response()->json(compact('result'));
        }

        $repost = $post
            ->reposts()
            ->where('profile_id', $currentProfile->id)
            ->first();

        if ($repost) {
            $result = $post->delete();

            return response()->json(compact('result'));
        }

        return response()->json(['result' => false], 403);
    }
}
