<?php

use App\Models\Follow;
use App\Models\Profile;

it('cannot follow a profile to itself', function (): void {
    $profile = Profile::factory()->create();

    Follow::createFollow($profile, $profile);
})->throws(InvalidArgumentException::class, 'A profile cannot follow to itself.');

it('can create a follow for two different profiles', function (): void {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);

    expect($follow->exists())->toBeTrue();
    expect(Follow::count())->toBe(1);
    expect($follow->follower->is($follower))->toBeTrue();
    expect($follow->following->is($following))->toBeTrue();
});

it('cannot unfollow the profile to itself', function (): void {
    $profile = Profile::factory()->create();

    Follow::removeFollow($profile, $profile);
})->throws(InvalidArgumentException::class, 'A profile cannot unfollow to itself.');

it('can remove the follow', function (): void {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);
    Follow::removeFollow($follower, $following);

    expect($follow->exists())->toBeFalse();
    expect(Follow::count())->toBe(0);
});
