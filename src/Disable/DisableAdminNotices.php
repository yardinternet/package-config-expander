<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisableAdminNotices
{
    public static function init(): void
    {
        if (current_user_can('manage_options')) {
            return;
        }

        // @phpstan-ignore-next-line
        add_action('admin_notices', 'update_nag', 3);
        add_action('admin_enqueue_scripts', [__CLASS__, 'adminThemeStyle']);
        add_action('login_enqueue_scripts', [__CLASS__, 'adminThemeStyle']);
    }

    /**
     * Hides the admin notices and WordPress footer for non admin users.
     * Gravityforms notice, dashboard (with version), footer (with version).
     */
    public static function adminThemeStyle(): void
    {
        echo '<style>#gf_dashboard_message, #wp-version-message, .update-nag, #yoast-indexation-warning, .footer-upgrade, #wpfooter, .gtm4wp-notice.notice{ display: none !important; }</style>';
    }
}
