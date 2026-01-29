<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
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
}
