<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

use WP_Rewrite;

class DisableFeed
{
    /**
     * Hook the actions to remove the feed from output.
     */
    public static function removeFeed(): void
    {
        add_action('do_feed', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_rdf', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_rss', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_rss2', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_atom', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_rss2_comments', [__CLASS__, 'disableFeed'], 1);
        add_action('do_feed_atom_comments', [__CLASS__, 'disableFeed'], 1);

        add_filter('the_generator', '__return_false', 10, 1);

        // Close all posts from pings
        add_filter('pings_open', '__return_false', 20);

        // Disable internal pingbacks
        add_action('pre_ping', [__CLASS__, 'removeInternalPingbacks'], 10, 1);

        // Disable x-pingback
        add_filter('wp_headers', [__CLASS__, 'removeXPingback'], 10, 1);

        // Remove feed links from the header
        add_action('wp_loaded', [__CLASS__, 'removeUnusedLinksFromHeader'], 1, 1);

        // Redirect all feeds to homepage
        add_action('template_redirect', [__CLASS__, 'filterIfNotFeed'], 1, 1);

        // Set pingback URI to blank for blog info
        add_filter('bloginfo_url', [__CLASS__, 'setPingbackUrlToBlank'], 1, 2);
        add_filter('bloginfo', [__CLASS__, 'setPingbackUrlToBlank'], 1, 2);
    }

    public static function disableFeed(): void
    {
        // @phpstan-ignore-next-line
        $output = sprintf(__('This link does not work, go to our website: <a href="%s">%s</a>.', 'config-expander'), get_home_url(), get_bloginfo('name'));

        wp_die($output);
    }

    /**
     * Unset each internal ping.
     *
     * @param array<string, string> $links
     */
    public static function removeInternalPingbacks(array &$links): void
    {
        foreach ($links as $l => $link) {
            $home = get_option('home');

            if (! is_string($home)) {
                continue;
            }

            if (0 === strpos($link, $home)) {
                unset($links[ $l ]);
            }
        }
    }

    /**
     * If the query is not a feed or 404 page, return.
     */
    public static function filterIfNotFeed(): void
    {
        if (! \is_feed() || \is_404()) {
            return;
        }
        // Call function to redirect feeds
        self::redirectFeeds();
    }

    /**
     * Redirect feeds to their appropiate destination.
     */
    private static function redirectFeeds(): void
    {
        // If the query contains `feed`, remove it from URL and redirect.
        if (isset($_GET['feed'])) {
            wp_safe_redirect(esc_url_raw(remove_query_arg('feed')), 301);
            exit;
        }

        // Ensure 'feed' query var is empty if not 'old'
        if ('old' !== get_query_var('feed')) {
            set_query_var('feed', '');
        }

        // Automatically redirect feed links to the proper URL.
        redirect_canonical();

        /** @var WP_Rewrite $wpRewrite */
        global $wpRewrite;

        // Build the feed URL structure for redirection
        $urlStruct = is_singular() ? '' : $wpRewrite->get_comment_feed_permastruct();
        $urlStruct = preg_quote($urlStruct ?: '', '//');
        $urlStruct = str_replace('%feed%', '(\w+)?', $urlStruct);
        $urlStruct = preg_replace('#//+/#', '/', $urlStruct);

        // Determine current and new URLs
        $urlCurrent = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $urlNew = preg_replace('#/' . $urlStruct . '/?$/#', '', $urlCurrent) ?? '';

        // Redirect if URLs differ
        if ($urlNew !== $urlCurrent) {
            wp_safe_redirect($urlNew, 301);
            exit;
        }
    }

    /**
     * If pingback URL is called, set it to blank.
     */
    public static function setPingbackUrlToBlank(string $output, string $show): string
    {
        return ('pingback_url' == $show) ? '' : $output;
    }

    /**
     * Unset x-pingback.
     *
     * @param array<string, string> $headers
     *
     * @return array<string, string> $headers
     */
    public static function removeXPingback(array $headers): array
    {
        unset($headers['X-Pingback']);

        return $headers;
    }

    /**
     * Get a list of header feed items, and remove them.
     */
    public static function removeUnusedLinksFromHeader(): void
    {
        $feed = [
            'feed_links'              => 2, // General feeds
            'feed_links_extra'        => 3, // Extra feeds
            'rsd_link'                => 10, // Really Simply Discovery & EditURI
            'wlwmanifest_link'        => 10, // Windows Live Writer manifest
            'index_rel_link'          => 10, // Index link
            'parent_post_rel_link'    => 10, // Prev link
            'start_post_rel_link'     => 10, // Start link
            'adjacent_posts_rel_link' => 10, // Relational links
            'wp_generator'            => 10, // WordPress version
            'wp_resource_hints'       => 2, // Resource Hints
        ];

        foreach ($feed as $function => $priority) {
            remove_action('wp_head', $function, $priority);
        }
    }

    /**
     * Based on the config, enable or disable XML RPC.
     */
    public static function disableXMLRPC(): void
    {
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('xmlrpc_methods', '__return_empty_array');
        add_filter('pre_option_enable_xmlrpc', '__return_zero', 10, 1);
    }

    /**
     * Add allowed methods in array format for the XML RPC protocol.
     *
     * @param array<string, string> $methods
     *
     * @return array<string, string> $methods
     */
    public static function XMLRPCAllowedMethods(array $methods): array
    {
        $xmlrpcMethods = resolve('config')->get('yard-config-expander.defaults.public.XMLRPC_ALLOWED_METHODS', []);

        if (empty($xmlrpcMethods)) {
            return $methods;
        }

        if (! is_array($xmlrpcMethods)) {
            $xmlrpcMethods = (array)$xmlrpcMethods;
        }

        foreach ($xmlrpcMethods as $xmlrpcAllowedMethod => $xmlrpcAllowedInternalMethod) {
            $methods[ $xmlrpcAllowedMethod ] = $xmlrpcAllowedInternalMethod;
        }

        return $methods;
    }

    /**
     * Disable editing of theme and plugin files.
     */
    public static function setFileEdit(bool $disallowFileEdit): void
    {
        if (defined('DISALLOW_FILE_EDIT')) {
            return;
        }

        define('DISALLOW_FILE_EDIT', $disallowFileEdit);
    }
}
