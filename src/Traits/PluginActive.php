<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Traits;

/**
 * This trait provides a workaround for scenarios where all plugins are disabled,
 * and the native WordPress functions for checking plugin activation status are not loaded.
 * It replicates the functionality of these native methods to ensure compatibility.
 */
trait PluginActive
{
    public function isPluginActive(string $plugin): bool
    {
        return in_array($plugin, (array) get_option('active_plugins', []), true) || $this->isPluginActiveForNetwork($plugin);
    }

    public function isPluginActiveForNetwork($plugin): bool
    {
        if (! is_multisite()) {
            return false;
        }

        $plugins = get_site_option('active_sitewide_plugins');

        if (isset($plugins[ $plugin ])) {
            return true;
        }

        return false;
    }
}
