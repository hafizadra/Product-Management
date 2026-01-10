<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileLayout extends Component
{
    public function __construct(
        public string $title = 'Profil',
        public array $sidebar = [],
        public ?string $pageTitle = null,
    ) {
    }

    public function render(): View
    {
        return view('components.profile-layout');
    }
}
