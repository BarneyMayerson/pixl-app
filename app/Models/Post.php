<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
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
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Post, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Like, $this>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Post, $this>
     */
    public function reposts(): HasMany
    {
        return $this->hasMany(Post::class, 'repost_of_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Post, $this>
     */
    public function repostOf(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'repost_of_id');
    }

    public static function publish(Profile $profile, string $content): self
    {
        return static::create([
            'profile_id' => $profile->id,
            'content' => $content,
            'parent_id' => null,
            'repost_of_id' => null,
        ]);
    }

    public static function reply(Profile $profile, Post $parent, string $content): self
    {
        return static::create([
            'profile_id' => $profile->id,
            'content' => $content,
            'parent_id' => $parent->id,
            'repost_of_id' => null,
        ]);
    }

    public static function repost(Profile $profile, Post $originalPost, ?string $content = null): self
    {
        return static::create([
            'profile_id' => $profile->id,
            'content' => $content,
            'parent_id' => null,
            'repost_of_id' => $originalPost->id,
        ]);
    }
}
