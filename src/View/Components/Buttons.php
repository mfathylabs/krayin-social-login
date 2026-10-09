<?php

namespace Krayin\SocialLogin\View\Components;

use Illuminate\View\Component;

class Buttons extends Component
{
    public array $providers;

    public function __construct(?array $providers = null)
    {
        $this->providers = $providers ?? app('social-login')->getProviders();
    }

    public function render()
    {
        return view('social-login::components.buttons');
    }
}
