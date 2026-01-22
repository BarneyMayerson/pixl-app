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
