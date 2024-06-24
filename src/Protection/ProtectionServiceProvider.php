<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use Illuminate\Support\ServiceProvider;

class ProtectionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('protect', function ($app) {
            return new Protect();
        });
    }

    public function boot(): void
    {
        $this->hooks();
    }

    private function hooks(): void
    {
        if (defined('WP_CLI') && WP_CLI) {
            return;
        }

        // @phpstan-ignore-next-line
        add_action('template_redirect', [resolve('protect'), 'handleSite'], 10, 0);
        // @phpstan-ignore-next-line
        add_action('login_init', [resolve('protect'), 'handleLogin'], 10, 0);
    }
}
