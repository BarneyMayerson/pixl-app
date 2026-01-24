<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/{profile:handle}', [ProfileController::class, 'show'])->name('profiles.show');

Route::get('/feed', function () {
    $feedItems = json_decode(json_encode([
        [
            'postedDateTime' => '3h',
            'content' => <<<'str'
 <p>I made this! <a href="#">#myartwork</a> <a href="#">#pixl</a></p>
<img src="/storage/images/simon-chilling.png" alt="" />
str,
            'likeCount' => 23,
            'replyCount' => 45,
            'repostCount' => 151,
            'profile' => [
                'avatar' => '/storage/images/michael.png',
                'displayName' => 'Michael',
                'handle' => '@mmich_jj',
            ],
            'replies' => [
                [
                    'postedDateTime' => '3h',
                    'content' => '<p>Heh — this looks just like me! UH-HAA FEED</p>',
                    'likeCount' => 4,
                    'replyCount' => 11,
                    'repostCount' => 27,
                    'profile' => [
                        'avatar' => '/storage/images/simon-chilling.png',
                        'displayName' => 'Simon',
                        'handle' => '@simonswiss',
                    ],
                ],
            ],
        ],
    ]));

    return view('feed', compact('feedItems'));
});

Route::get('/profile', function () {
    $feedItems = json_decode(json_encode([
        [
            'postedDateTime' => '3h',
            'content' => <<<'str'
 <p>I made this! <a href="#">#myartwork</a> <a href="#">#pixl</a></p>
<img src="/storage/images/simon-chilling.png" alt="" />
str,
            'likeCount' => 23,
            'replyCount' => 45,
            'repostCount' => 151,
            'profile' => [
                'avatar' => '/storage/images/michael.png',
                'displayName' => 'Michael',
                'handle' => '@mmich_jj',
            ],
            'replies' => [
                [
                    'postedDateTime' => '3h',
                    'content' => '<p>Heh — this looks just like me! UGH PROFILE</p>',
                    'likeCount' => 4,
                    'replyCount' => 11,
                    'repostCount' => 27,
                    'profile' => [
                        'avatar' => '/storage/images/simon-chilling.png',
                        'displayName' => 'Simon',
                        'handle' => '@simonswiss',
                    ],
                ],
            ],
        ],
    ]));

    return view('profile', compact('feedItems'));
});
