<?php

use App\Models\Post;
use App\Models\Profile;

it('allows a profile to publish a post', function (): void {
    $profile = Profile::factory()->create();

    $post = Post::publish($profile, 'This is content of the post.');

    expect($post->exists())->toBeTrue();
    expect($post->content)->toBe('This is content of the post.');
    expect($post->profile->is($profile))->toBeTrue();
    expect($post->parent_id)->toBeNull();
    expect($post->repost_of_id)->toBeNull();
});

it('can reply to post', function (): void {
    $originPost = Post::factory()->create();
    $replier = Profile::factory()->create();

    $reply = Post::reply($replier, $originPost, 'This is a reply.');

    expect($reply->exists())->toBeTrue();
    expect($reply->parent->is($originPost))->toBeTrue();
    expect($originPost->replies->first()->is($reply))->toBeTrue();
});

it('can have many repliest', function (): void {
    $originPost = Post::factory()->create();
    $replies = Post::factory(4)->create([
        'parent_id' => $originPost->id,
    ]);

    expect($replies->first()->parent->is($originPost))->toBeTrue();
    expect($originPost->replies->count())->toBe(4);
});

it('creates plain repost', function (): void {
    $originPost = Post::factory()->create();
    $reposter = Profile::factory()->create();

    $repost = Post::repost($reposter, $originPost);

    expect($repost->exists())->toBeTrue();
    expect($repost->content)->toBeNull();
    expect($originPost->reposts)->toHaveCount(1);
});

it('can have many reposts', function (): void {
    $originPost = Post::factory()->create();
    $reposts = Post::factory(3)->create([
        'repost_of_id' => $originPost->id,
    ]);

    expect($reposts->first()->repostOf->is($originPost))->toBeTrue();
    expect($originPost->reposts->count())->toBe(3);
});

it('creates quote repost', function (): void {
    $originPost = Post::factory()->create();
    $reposter = Profile::factory()->create();
    $content = 'Check this out!';

    $repost = Post::repost($reposter, $originPost, $content);

    expect($repost->exists())->toBeTrue();
    expect($repost->content)->toBe($content);
    expect($originPost->reposts)->toHaveCount(1);
});
