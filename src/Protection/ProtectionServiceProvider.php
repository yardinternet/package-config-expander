<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use Illuminate\Support\ServiceProvider;
use WP_Admin_Bar;

class ProtectionServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->singleton('protect', function ($app) {
			return new Protect();
		});
	}

	public function boot(): void
	{
		$this->initProtection();
		$this->hooks();

		add_filter('varnish_http_purge_events', [$this, 'addCustomPurgeEvent']);
		add_filter('varnish_http_purge_events_full', [$this, 'addCustomPurgeEvent']);
	}

	private function hooks(): void
	{
		add_action('admin_bar_menu', [$this, 'showProtectionStatus'], 9999, 1);
	}

	public function showProtectionStatus(WP_Admin_Bar $adminBar): void
	{
		if (! current_user_can('manage_options') || ! function_exists('get_field')) {
			return;
		}

		$type = get_field('type_protection_website', 'options');

		if (empty($type) || 'none' === $type) {
			return;
		}

		//TODO clearner to adjust inline styling to css file
		$adminBar->add_node([
			'id' => 'protection-status',
			'title' => '<span style="background:#e63946;color:#fff;padding:0 8px;border-radius:4px;display:inline-block;">Site is afgeschermd!</span>',
		]);
	}

	private function initProtection(): void
	{
		if (! $this->shouldInitProtection()) {
			return;
		}

		// First check admin pages and login page.
		if (strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false || strpos($_SERVER['REQUEST_URI'], '/wp-login') !== false) {
			add_action('init', function () {
				resolve('protect')->handleLogin();
			});

			return;
		}

		// @phpstan-ignore-next-line
		add_action('template_redirect', [resolve('protect'), 'handleSite'], 10, 0);
	}

	/**
	 * Add custom purge event.
	 *
	 * @param  string[]  $actions
	 *
	 * @return string[]
	 */
	public function addCustomPurgeEvent(array $actions): array
	{
		$extra = [
			'yard::config-expander/acf/settings-updated',
		];

		return array_merge($actions, $extra);
	}

	private function shouldInitProtection(): bool
	{
		if (defined('WP_CLI') && WP_CLI || (defined('WP_ENV') && WP_ENV === 'development')) {
			return false;
		}

		if (is_user_logged_in()) {
			return false;
		}

		return true;
	}
}
