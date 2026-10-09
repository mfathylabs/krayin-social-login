<?php

namespace Krayin\SocialLogin\Tests;

use Orchestra\Testbench\TestCase;
use Krayin\SocialLogin\Providers\SocialLoginServiceProvider;
use Krayin\SocialLogin\SocialLoginManager;

class SocialLoginManagerTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [SocialLoginServiceProvider::class];
    }

    public function test_manager_is_bound_in_container(): void
    {
        $this->assertInstanceOf(SocialLoginManager::class, app('social-login'));
    }

    public function test_returns_empty_providers_when_none_configured(): void
    {
        $manager = app('social-login');
        $this->assertEmpty($manager->getProviders());
    }

    public function test_returns_configured_providers(): void
    {
        config(['services.google.client_id' => 'test-id']);

        $manager = app('social-login');
        $providers = $manager->getProviders();

        $this->assertContains('google', $providers);
    }

    public function test_supported_providers_list(): void
    {
        $manager = app('social-login');
        $supported = $manager->getSupportedProviders();

        $this->assertContains('google', $supported);
        $this->assertContains('github', $supported);
        $this->assertContains('linkedin', $supported);
        $this->assertContains('twitter', $supported);
    }
}
