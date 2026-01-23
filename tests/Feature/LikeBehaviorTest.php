<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;

it('can create a like for the post', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $like = Like::createLike($profile, $post);

    expect($like->exists())->toBeTrue();
    expect($like->post->is($post))->toBeTrue();
    expect($like->profile->is($profile))->toBeTrue();
    expect($profile->likes)->toHaveCount(1);
    expect($post->likes)->toHaveCount(1);
});

it('cannot create duplicate likes', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $likeOne = Like::createLike($profile, $post);
    $likeTwo = Like::createLike($profile, $post);

    expect($likeTwo->is($likeOne))->toBeTrue();
    expect(Like::count())->toBe(1);
});

it('can remove the like', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $like = Like::createLike($profile, $post);
    Like::removeLike($profile, $post);

    expect($like->exists())->toBeFalse();
    expect($profile->likes)->toHaveCount(0);
    expect($post->likes)->toHaveCount(0);
});
