<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Queries\PostThreadQuery;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;

        $posts = TimelineQuery::forViewer($profile)->get();

        return Inertia::render('Posts/Index', [
            'posts' => $posts->toResourceCollection(),
            'profile' => $profile->toResource(),
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        $profile = Auth::user()->profile;
        $post = Post::publish($profile, $request->validated()['content']);

        return to_route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Profile $profile, Post $post)
    {
        $post = PostThreadQuery::for($post, Auth::user()?->profile)->load();

        return Inertia::render('Posts/Show', ['post' => $post->toResource()]);
    }

    public function reply(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $replier = Auth::user()->profile;

        Post::reply($replier, $post, $request->content);

        return back();
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

        Like::createLike($liker, $post);

        return back();
    }

    public function unlike(Profile $profile, Post $post)
    {
        $liker = Auth::user()->profile;

        Like::removeLike($liker, $post);

        return back();
    }

    public function destroy(Profile $profile, Post $post)
    {
        if (Auth::user()->can('update', $post)) {
            $post->delete();
        }

        $post
            ->reposts()
            ->where('profile_id', Auth::user()->profile->id)
            ->first()
            ?->delete();

        return back();
    }
}
