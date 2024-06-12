<?php

declare(strict_types=1);

namespace Yard\ConfigExpander;

use Illuminate\Support\ServiceProvider;

class ConfigExpanderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/example.php',
            'example'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/example.php' => $this->app->configPath('example.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
