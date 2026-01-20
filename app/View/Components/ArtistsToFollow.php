<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArtistsToFollow extends Component
{
    public array $artists;

    public function __construct()
    {
        $this->artists = [
            ['name' => 'Alessia Draws', 'handle' => 'alessia', 'image' => '/storage/images/alessia.png'],
            ['name' => 'Anne', 'handle' => 'anne', 'image' => '/storage/images/anne.png'],
            ['name' => 'Mr Anderson', 'handle' => 'anderson', 'image' => '/storage/images/mr-anderson.png'],
            ['name' => 'Michael', 'handle' => 'michael', 'image' => '/storage/images/michael.png'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.artists-to-follow', [$artists = $this->artists]);
    }
}
