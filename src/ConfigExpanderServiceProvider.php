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
            __DIR__ . '/../config/yard-config-expander.php',
            'yard-config-expander'
        );

        $this->callServiceProviders('register');

        load_textdomain('config-expander', __DIR__ . '/../languages/config-expander-nl_NL.mo');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/yard-config-expander.php' => $this->app->configPath('yard-config-expander.php'),
        ], 'config');

        $this->callServiceProviders('boot');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    private function callServiceProviders(string $method = ''): void
    {
        if (empty($method)) {
            throw new Exception('No method specified which can be used to call specific method of a service provider.');
        }

        $providers = resolve('config')->get('yard-config-expander.providers', []);

        if (! is_array($providers)) {
            return;
        }

        foreach ($providers as $provider => $args) {
            if (! class_exists($provider) || ! ($args['enabled'] ?? false)) {
                continue;
            }

            $class = new $provider($this->app);

            if (! is_object($class)) {
                continue;
            }

            $class->$method();
        }
    }
}
