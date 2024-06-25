<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup\Plugins;

abstract class BaseAbstract
{
    protected string $plugin = '';

    abstract public function cleanup(): void;

    protected function pluginIsActive(): bool
    {
        if (empty($this->plugin)) {
            return false;
        }

        return is_plugin_active($this->plugin);
    }

    protected function pluginIsNetworkActived(): bool
    {
        if (empty($this->plugin)) {
            return false;
        }

        return is_plugin_active_for_network($this->plugin);
    }
}
