<?php

namespace Krayin\SocialLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getProviders()
 * @method static array getSupportedProviders()
 * @method static mixed redirect(string $provider)
 * @method static mixed handleCallback(string $provider)
 * @method static string getRedirectUrl(string $provider)
 *
 * @see \Krayin\SocialLogin\SocialLoginManager
 */
class SocialLogin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'social-login';
    }
}
