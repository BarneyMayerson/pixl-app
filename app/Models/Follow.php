<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Follow extends Model
{
    /** @use HasFactory<\Database\Factories\FollowFactory> */
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Profile, $this>
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'follower_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Profile, $this>
     */
    public function following(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'following_profile_id');
    }

    public static function createFollow(Profile $follower, Profile $following): self
    {
        if ($follower->is($following)) {
            throw new InvalidArgumentException('A profile cannot follow to itself.');
        }

        return static::firstOrCreate([
            'follower_profile_id' => $follower->id,
            'following_profile_id' => $following->id,
        ]);
    }

    public static function removeFollow(Profile $follower, Profile $following): void
    {
        if ($follower->is($following)) {
            throw new InvalidArgumentException('A profile cannot unfollow to itself.');
        }

        static::query()
            ->where('follower_profile_id', $follower->id)
            ->where('following_profile_id', $following->id)
            ->delete();
    }
}
