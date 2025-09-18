<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use Illuminate\Support\ServiceProvider;
use WP_Admin_Bar;
use Yard\ConfigExpander\Traits\WordPressEnvironment;

class ProtectionServiceProvider extends ServiceProvider
{
	use WordPressEnvironment;

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
	}

	private function hooks(): void
	{
		add_action('admin_bar_menu', $this->showProtectionStatus(...), 9999, 1);
		add_filter('varnish_http_purge_events', $this->addCustomPurgeEvent(...));
		add_filter('varnish_http_purge_events_full', $this->addCustomPurgeEvent(...));
	}

	public function showProtectionStatus(WP_Admin_Bar $adminBar): void
	{
		if ($this->isDevelopmentEnvironment()) {
			return;
		}

		if (! current_user_can('manage_options') || ! function_exists('get_field')) {
			return;
		}

		$type = get_field('type_protection_website', 'options');

		if (empty($type) || 'none' === $type) {
			return;
		}

		// TODO clearner to adjust inline styling to css file
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

		/** @var Protect $protect */
		$protect = resolve('protect');

		// First check admin pages and login page.
		if (strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false || strpos($_SERVER['REQUEST_URI'], '/wp-login') !== false) {
			add_action('init', function () use ($protect) {
				$protect->handleLogin();
			});

			return;
		}

		add_action('template_redirect', $protect->handleSite(...), 10, 0);
	}

	/**
	 * @param string[] $actions
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
