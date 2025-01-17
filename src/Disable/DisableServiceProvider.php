<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

use Illuminate\Support\ServiceProvider;

class DisableServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		//
	}

	public function boot(): void
	{
		$this->disables();
	}

	protected function disables(): void
	{
		$settings = resolve('config')->get('yard-config-expander.defaults', []);

		if (! is_array($settings)) {
			return;
		}

		$this->disablesAdmin($settings['admin'] ?? []);
		$this->disablesAPI($settings['api'] ?? []);
		$this->disablesPublic($settings['public'] ?? []);
	}

	/**
	 * @param array<string, mixed> $settings
	 */
	protected function disablesAdmin(array $settings): void
	{
		if ($settings['AUTOMATIC_UPDATER_DISABLED'] ?? true) {
			add_filter('automatic_updater_disabled', '__return_true');
		}

		if ($settings['UNSET_ADMIN_ROLE_FOR_NON_ADMINS'] ?? true) {
			add_filter('editable_roles', [DisableRoles::class, 'unsetAdminRoleForNonAdmins']);
		}

		if (! defined('WP_CACHE') && is_bool($settings['WP_CACHE'] ?? false)) {
			define('WP_CACHE', $settings['WP_CACHE'] ?? false);
		}

		if (! empty($settings['AUTOSAVE_INTERVAL']) && is_numeric($settings['AUTOSAVE_INTERVAL']) && ! defined('AUTOSAVE_INTERVAL')) {
			define('AUTOSAVE_INTERVAL', $settings['AUTOSAVE_INTERVAL']);
		}

		if ($settings['DISABLE_POSTS'] ?? true) {
			DisablePosts::init();
		}

		if ($settings['DISABLE_COMMENTS'] ?? true) {
			DisableComments::init();
		}

		if ($settings['DISABLE_ADMIN_NOTICES_FOR_NON_ADMINS'] ?? true) {
			DisableAdminNotices::init();
		}

		DisableFeed::setFileEdit((bool) ($settings['DISALLOW_FILE_EDIT'] ?? false));
	}

	/**
	 * @param array<string, mixed> $settings
	 */
	protected function disablesAPI(array $settings): void
	{
		if ($settings['DISABLE_REST_API_OEMBED'] ?? true) {
			add_action('wp_loaded', [DisableOEmbedEndpoint::class, 'init']);
		}

		if ($settings['DISABLE_REST_API_USERS'] ?? true) {
			add_action('wp_loaded', [DisableUsersEndpoint::class, 'init']);
		}
	}

	/**
	 * @param array<string, mixed> $settings
	 */
	protected function disablesPublic(array $settings): void
	{
		if (! ($settings['FEEDS_ENABLED'] ?? false)) {
			DisableFeed::removeFeed();
		}

		if (! ($settings['XMLRPC_ENABLED'] ?? false)) {
			DisableFeed::disableXMLRPC();
		} else {
			add_filter('xmlrpc_methods', [DisableFeed::class, 'XMLRPCAllowedMethods']);
		}

		if (($settings['CLEANUP_HEADERS'] ?? true)) {
			DisableHeaders::init();
		}

		if (($settings['DISABLE_EMOJICONS'] ?? true)) {
			DisableEmojicons::init();
		}

		// Disable WordPress sitemaps to prevent user data from being publicly exposed.
		add_filter('wp_sitemaps_enabled', '__return_false');
	}
}
