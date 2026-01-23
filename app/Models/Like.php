<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Profile, $this>
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public static function createLike(Profile $profile, POst $post): self
    {
        return static::firstOrCreate([
            'profile_id' => $profile->id,
            'post_id' => $post->id,
        ]);
    }

    public static function removeLike(Profile $profile, Post $post): void
    {
        static::query()
            ->where('profile_id', $profile->id)
            ->where('post_id', $post->id)
            ->delete();
    }
}
