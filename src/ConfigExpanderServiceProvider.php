<?php

declare(strict_types=1);

namespace Yard\ConfigExpander;

use Exception;
use Illuminate\Support\ServiceProvider;

class ConfigExpanderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/yard-config-expander.php',
            'yard-config-expander'
        );

        $this->callServiceProviders('register');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/yard-config-expander.php' => $this->app->configPath('yard-config-expander.php'),
        ], 'config');

        $this->callServiceProviders('boot');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    protected function callServiceProviders(string $method = '')
    {
        if (empty($method)) {
            throw new Exception('No method specified which can be used to call specific method of a service provider.');
        }

        $configFile = resolve('config')->get('yard-config-expander', []);
        $providers = $configFile['providers'] ?? [];

        foreach ($providers as $provider => $args) {
            if (! $args['enabled'] ?? false) {
                continue;
            }

            (new $provider($this->app))->$method();
        }
    }
}
