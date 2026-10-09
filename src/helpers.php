<?php

/**
 * Krayin Social Login — Helper Functions
 *
 * Convenient shorthand for common social login operations.
 * Auto-loaded via composer.json "files" directive.
 */

if (!function_exists('social_login_providers')) {
    /**
     * Get the list of configured social login providers.
     */
    function social_login_providers(): array
    {
        return app('social-login')->getProviders();
    }
}

if (!function_exists('social_login_url')) {
    /**
     * Generate the redirect URL for a social login provider.
     */
    function social_login_url(string $provider): string
    {
        return app('social-login')->getRedirectUrl($provider);
    }
}

if (!function_exists('social_login_enabled')) {
    /**
     * Check if any social login provider is configured.
     */
    function social_login_enabled(): bool
    {
        return count(social_login_providers()) > 0;
    }
}

