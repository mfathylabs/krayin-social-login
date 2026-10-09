<?php

namespace Krayin\SocialLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Krayin\SocialLogin\Facades\SocialLogin;

class SocialLoginController extends Controller
{
    /**
     * Redirect to the OAuth provider.
     */
    public function redirect(string $provider)
    {
        return SocialLogin::redirect($provider);
    }

    /**
     * Handle the callback from the OAuth provider.
     */
    public function callback(string $provider)
    {
        return SocialLogin::handleCallback($provider);
    }
}
