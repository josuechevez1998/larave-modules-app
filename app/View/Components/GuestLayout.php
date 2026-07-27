<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public string $institutionName;

    public ?string $institutionLogoUrl;

    public ?string $institutionTagline;

    public function __construct()
    {
        $this->institutionName = institution_name();
        $this->institutionLogoUrl = institution_logo_url();
        $this->institutionTagline = institution_tagline();
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
