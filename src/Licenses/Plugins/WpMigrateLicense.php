<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Plugins;

use WP_User;
use WP_User_Query;
use Yard\ConfigExpander\Licenses\Contracts\AbstractLicense;

class WpMigrateLicense extends AbstractLicense
{
    protected const LICENSE_CONFIG_KEY = 'WPMDB_LICENSE_KEY';
    protected const PLUGIN_LICENSE_CONSTANT = 'WPMDB_LICENCE'; // Typo is present in the plugin as well.

    public function register(): void
    {
        if (empty($this->getLicense()) || $this->isLicenseConstant()) {
            return;
        }

        if (! $this->isPluginActive('wp-migrate-db-pro/wp-migrate-db-pro.php') || ! $this->pluginIsInstalled()) {
            return;
        }

        $this->licenseAsUserMeta();
    }

    /**
     * Check if constant is defined.
     * If so, the license is already handled by the plugin.
     */
    protected function isLicenseConstant(): bool
    {
        return defined(self::PLUGIN_LICENSE_CONSTANT);
    }

    /**
     * Check if WP migrate plugin is installed.
     */
    protected function pluginIsInstalled(): bool
    {
        if (! function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $installedPlugins = get_plugins();

        return array_key_exists('wp-migrate-db-pro/wp-migrate-db-pro.php', $installedPlugins);
    }

    /**
     * License is stored in the usermeta table.
     */
    protected function licenseAsUserMeta(): void
    {
        $user = $this->getYardAdminUser();

        if (! $user instanceof WP_User) {
            return;
        }

        update_user_meta($user->ID, 'wpmdb_licence_key', $this->getLicense());
    }

    protected function getYardAdminUser(): ?WP_User
    {
        $args = [
            'search' => '*@yard.nl*',
            'search_columns' => ['user_email'],
            'role' => 'Administrator',
        ];

        $userQuery = new WP_User_Query($args);

        return $userQuery->results[0] ?? null;
    }
}
