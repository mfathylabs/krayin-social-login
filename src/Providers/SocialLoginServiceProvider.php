<?php

namespace Krayin\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Krayin\SocialLogin\SocialLoginManager;

class SocialLoginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'social-login');

        $this->mergeConfigFrom(__DIR__ . '/../Config/social.php', 'social-login');

        $this->publishes([
            __DIR__ . '/../Config/social.php' => config_path('social-login.php'),
        ], 'social-login-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/social-login'),
        ], 'social-login-views');

        $this->loadViewComponentsAs('social-login', [
            \Krayin\SocialLogin\View\Components\Buttons::class,
        ]);

    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('social-login', function ($app) {
            return new SocialLoginManager($app);
        });
    }
}
