<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisablePosts
{
    /**
     * Set up actions and filters.
     */
    public static function init(): void
    {
        add_action('current_screen', [__CLASS__, 'redirectPostsViewToDashboard']);
        add_action('admin_menu', [__CLASS__, 'removePostsFromMenu']);
        add_action('wp_before_admin_bar_render', [__CLASS__, 'removePostsFromAdminToolbar']);
        add_action('admin_init', [__CLASS__, 'removeDashboardWidgets']);
        add_action('wp_head', [__CLASS__, 'restore_rss_feed'], 1); // Restore the main feed.

        remove_action('wp_head', 'feed_links', 2); // Remove main feeds.
        remove_action('wp_head', 'feed_links_extra', 3); // Remove post comments feed.

        add_filter('customize_nav_menu_available_item_types', [__CLASS__, 'removePostsFromCustomizer']);
        add_action('admin_init', [__CLASS__, 'redirectPostToDashboard'], 10, 0);
        add_action('admin_init', [__CLASS__, 'removeDashboardWidgetServeHappyNag'], 10, 0);
        add_action('wp_dashboard_setup', [__CLASS__, 'disableDashboardWidgetServeHappyNag'], 10, 0);
        add_filter('wp_count_posts', [__CLASS__, 'removePostOnAtAGlanceDashboard'], 10, 3);
        add_action('rest_api_init', [__CLASS__, 'removePublicFacingSettingsForPosts']);

        self::removePublicFacingSettingsForPosts();
    }

    /**
     * Remove the public facing settings for 'Posts'
     */
    public static function removePublicFacingSettingsForPosts(): void
    {
        global $wp_post_types;

        if (! isset($wp_post_types['post'])) {
            return;
        }

        $wp_post_types['post']->set_props([
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'can_export'          => false,
            'show_in_rest'        => false,
        ]);
    }

    /**
     * Remove the post count on the dashboard 'At a glance'.
     */
    public static function removePostOnAtAGlanceDashboard(object $counts, string $type, string $perm): ?object
    {
        if (! is_admin()) {
            return $counts;
        }

        $screen = \get_current_screen();

        if (null === $screen) {
            return $counts;
        }

        if ('dashboard' !== $screen->id) {
            return $counts;
        }

        if ('post' !== $type) {
            return $counts;
        }

        return null;
    }

    /**
     * Restrict access to the posts view, and redirect to dashboard.
     */
    public static function redirectPostsViewToDashboard(): void
    {
        $screen = get_current_screen();

        if (null === $screen) {
            return;
        }

        if ('post' !== $screen->post_type) {
            return;
        }

        if (! in_array($screen->id, ['post', 'edit-post'])) {
            return;
        }

        wp_safe_redirect(\get_admin_url());
        exit;
    }

    /*
     * Alter admin menu links.
     */
    public static function removePostsFromMenu(): void
    {
        // We don’t need the posts edit page.
        remove_menu_page('edit.php');
    }

    /**
     * Remove the creation of posts in the customizer.
     *
     * @param array<int, array{title: string, type_label: string, type: string, object: string}> $itemTypes
     *
     * @return array<int, array{title: string, type_label: string, type: string, object: string}> $itemTypes
     */
    public static function removePostsFromCustomizer(array $itemTypes): array
    {
        return array_filter($itemTypes, function ($postType) {
            return ! empty($postType['object']) && ('post' !== $postType['object']);
        });
    }

    /**
     * Disable Dashboard Widget Serve Happy Nag
     */
    public static function disableDashboardWidgetServeHappyNag(): void
    {
        remove_meta_box('dashboard_php_nag', 'dashboard', 'normal');
        remove_meta_box('dashboard_php_nag', 'dashboard', 'side');
        remove_meta_box('dashboard_php_nag', 'dashboard-network', 'normal');
        remove_meta_box('dashboard_php_nag', 'dashboard-network', 'side');
    }

    /**
     * Disable Dashboard Widget Serve Happy Nag
     */
    public static function removeDashboardWidgetServeHappyNag(): void
    {
        remove_filter('dashboard_php_nag', 'wp_dashboard_php_nag');
    }

    /*
     * Customize links in admin bar.
     */
    public static function removePostsFromAdminToolbar(): void
    {
        if (! is_admin_bar_showing()) {
            return;
        }

        global $wp_admin_bar;

        // Alter the New link.
        $newContentLink = $wp_admin_bar->get_node('new-content');

        if (isset($newContentLink->href)) {
            $newContentLink->href .= '?post_type=page';
            $wp_admin_bar->add_node($newContentLink);
        }

        // Remove link to add a new Post.
        $wp_admin_bar->remove_menu('new-post', 'new-content');
    }

    /**
     * Disable Dashboard widgets.
     */
    public static function removeDashboardWidgets(): void
    {
        // Remove Quick Draft since we aren’t making use of Posts.
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
        remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'side');
        remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');
        remove_action('welcome_panel', 'wp_welcome_panel');
    }

    /**
     * Output main feed link.
     */
    public static function restore_rss_feed(): void
    {
        echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('sitename') . ' &raquo; Feed" href="' . get_bloginfo('rss2_url') . '">' . "\n";
    }

    public static function redirectPostToDashboard(): void
    {
        $request = trim($_SERVER['REQUEST_URI'], '/');
        $blacklist = ['wp-admin/post-new.php', 'wp-admin/edit.php'];

        if (! in_array($request, $blacklist)) {
            return;
        }

        wp_safe_redirect(get_admin_url());
        exit();
    }
}
