<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $profiles = Profile::factory(20)->create();

        foreach ($profiles as $profile) {
            $toFollow = $profiles->random(random_int(2, 4))->except($profile->id);

            foreach ($toFollow as $followProfile) {
                Follow::createFollow($profile, $followProfile);
            }
        }

        $posts = Post::factory(50)->recycle($profiles)->create();

        foreach ($profiles as $profile) {
            $profilePosts = $posts->where('profile_id', $profile->id);

            foreach ($profilePosts as $post) {
                $toLike = $profiles->random(random_int(1, 5))->except($profile->id);

                foreach ($toLike as $likeProfile) {
                    Like::createLike($likeProfile, $post);
                }
            }
        }

        foreach ($profiles as $profile) {
            $profilePosts = $posts->where('profile_id', $profile->id);

            foreach ($profilePosts as $repost) {
                $toRepost = $profiles->random(random_int(1, 2))->except($profile->id);

                foreach ($toRepost as $repostProfile) {
                    Post::repost($repostProfile, $repost, random_int(0, 1) ? 'Great post!' : null);
                }
            }
        }

        for ($i = 0; $i < random_int(20, 30); $i++) {
            $parentPost = $posts->random();
            $replier = $profiles->where('id', '!=', $parentPost->profile_id)->random();

            Post::factory()->reply($parentPost)->create(['profile_id' => $replier->id]);
        }
    }
}
