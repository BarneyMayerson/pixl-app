<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Profile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ArtistsToFollow extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (Auth::guest()) {
            $query = Profile::query();
        } else {
            /** @var Profile $profile */
            $profile = Auth::user()->profile;

            $sql = Profile::query()
                ->whereDoesntHave('followers', fn ($q) => $q->where('follower_profile_id', $profile->id))
                ->where('id', '!=', $profile->id)
                ->inRandomOrder()
                ->take(4)
                ->toSql();

            Log::info('ArtistsToFollow SQL: '.$sql);

            $query = Profile::query()
                ->whereDoesntHave('followers', fn ($q) => $q->where('follower_profile_id', $profile->id))
                ->where('id', '!=', $profile->id);

        }

        $profiles = $query->inRandomOrder()->take(4)->get();

        return view('components.artists-to-follow', compact('profiles'));
    }
}
