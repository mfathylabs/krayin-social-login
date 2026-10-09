<?php

namespace Krayin\SocialLogin;

use Illuminate\Foundation\Application;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginManager
{
    protected Application $app;

    protected array $supportedProviders = [
        'google',
        'github',
        'linkedin',
        'twitter',
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get all providers that have credentials configured.
     */
    public function getProviders(): array
    {
        return array_values(array_filter($this->supportedProviders, function ($provider) {
            return !empty(config("services.{$provider}.client_id"));
        }));
    }

    /**
     * Get all supported providers regardless of config.
     */
    public function getSupportedProviders(): array
    {
        return $this->supportedProviders;
    }

    /**
     * Redirect user to OAuth provider.
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth callback and authenticate user.
     */
    public function handleCallback(string $provider)
    {
        $this->validateProvider($provider);

        $socialUser = Socialite::driver($provider)->user();

        $user = $this->findOrCreateUser($socialUser, $provider);

        auth()->login($user, true);

        return redirect(config('social-login.redirect_after_login', '/admin/dashboard'));
    }

    /**
     * Get the redirect URL for a provider.
     */
    public function getRedirectUrl(string $provider): string
    {
        return route('social-login.redirect', ['provider' => $provider]);
    }

    /**
     * Find existing user or create a new one from social profile.
     */
    protected function findOrCreateUser($socialUser, string $provider)
    {
        $user = \DB::table('users')->where('email', $socialUser->getEmail())->first();

        if ($user) {
            return $user;
        }

        if (!config('social-login.auto_register', true)) {
            abort(403, 'Registration via social login is disabled.');
        }

        $userId = \DB::table('users')->insertGetId([
            'name'       => $socialUser->getName() ?? $socialUser->getNickname(),
            'email'      => $socialUser->getEmail(),
            'password'   => bcrypt(str()->random(32)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return \DB::table('users')->find($userId);
    }

    /**
     * Validate that the provider is supported and configured.
     */
    protected function validateProvider(string $provider): void
    {
        if (!in_array($provider, $this->supportedProviders)) {
            abort(400, "Unsupported social login provider: {$provider}");
        }

        if (empty(config("services.{$provider}.client_id"))) {
            abort(500, "Social login provider '{$provider}' is not configured.");
        }
    }
}
