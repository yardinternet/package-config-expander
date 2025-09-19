<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisableComments
{
	public static function init(): void
	{
		add_action('wp_loaded', self::disableCommentsPostTypesSupport(...));
		add_filter('comments_array', self::disableCommentsHideExistingComments(...), 10, 2);
		add_action('admin_menu', self::removeCommentsAdminMenu(...));
		add_action('admin_init', self::removeCommentsAdminMenuRedirect(...));
		add_action('admin_init', self::removeCommentsWidgetDashboard(...));
		add_action('add_admin_bar_menus', self::removeCommentsItemAdminBar(...));

		add_filter('manage_pages_columns', self::removeCommentsColumns(...));
		add_filter('manage_media_columns', self::removeCommentsColumns(...));
		add_filter('get_comments_number', self::disableFeedCommentsRemoveCount(...), 10, 2);
		add_filter('post_comments_feed_link', self::disableFeedCommentsRemoveLinks(...), 10, 1);
		add_filter('comments_link_feed', self::disableFeedCommentsRemoveLinks(...), 10, 1);
		add_filter('comment_link', self::disableFeedCommentsRemoveLinks(...), 10, 1);
		add_filter('comments_open', '__return_false', 20, 0);
		add_filter('pings_open', '__return_false', 20, 0);
	}

	/**
	 * Remove comments column from Pages list.
	 *
	 * @param array<string, string> $columns
	 *
	 * @return array<string, string> $columns
	 */
	public static function removeCommentsColumns(array $columns): array
	{
		unset($columns['comments']);

		return $columns;
	}

	/**
	 * Disable support for comments and trackbacks in post types.
	 */
	public static function disableCommentsPostTypesSupport(): void
	{
		foreach (get_post_types([], 'names') as $postType) {
			\remove_post_type_support($postType, 'comments');
			\remove_post_type_support($postType, 'trackbacks');
		}
	}

	/**
	 * Hide existing comments.
	 *
	 * @param array<string, string> $comments
	 *
	 * @return array<string, string> $comments
	 */
	public static function disableCommentsHideExistingComments(array $comments, int $postID): array
	{
		$comments = [];

		return $comments;
	}

	/**
	 * Remove comments page in menu.
	 */
	public static function removeCommentsAdminMenu(): void
	{
		remove_menu_page('edit-comments.php');
	}

	/**
	 * Redirect any user trying to access comments page.
	 */
	public static function removeCommentsAdminMenuRedirect(): void
	{
		global $pagenow;

		$blacklist = ['edit-comments.php'];

		if (! in_array($pagenow, $blacklist)) {
			return;
		}

		wp_safe_redirect(get_admin_url());
		exit();
	}

	/**
	 *  Remove comments widget from dashboard.
	 */
	public static function removeCommentsWidgetDashboard(): void
	{
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	}

	/**
	 * Remove comments links from admin bar.
	 */
	public static function removeCommentsItemAdminBar(): void
	{
		if (is_admin_bar_showing()) {
			remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
		}
	}

	/**
	 * Remove menus from the admin bar.
	 */
	public static function remove_admin_bar_menus(): void
	{
		if (! is_admin_bar_showing()) {
			return;
		}

		// Remove comments menu from admin bar.
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}

	/**
	 *  Disable comments count within RSS.
	 */
	public static function disableFeedCommentsRemoveCount(int $count, int $postID): int
	{
		if (is_feed()) {
			return 0;
		}

		return $count;
	}

	/**
	 * Remove all links to the comments feed
	 */
	public static function disableFeedCommentsRemoveLinks(string $link): string
	{
		return '';
	}
}
