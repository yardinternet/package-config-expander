<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use Illuminate\Support\ServiceProvider;
use WP_Admin_Bar;
use Yard\ConfigExpander\Support\Helpers\WordPressEnvironment;
use Yard\ConfigExpander\Support\Traits\Route;

class ProtectionServiceProvider extends ServiceProvider
{
	use Route;

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
		add_action('admin_enqueue_scripts', $this->enqueueProtectionStyles(...));
		add_action('admin_bar_menu', $this->showProtectionStatus(...), 9999, 1);
		add_filter('varnish_http_purge_events', $this->addCustomPurgeEvent(...));
		add_filter('varnish_http_purge_events_full', $this->addCustomPurgeEvent(...));
	}

	public function enqueueProtectionStyles(): void
	{
		$source = apply_filters('yard::config-expander/protection-status/style-file', $this->route('/yard/config-expander/resources/css/protection.css'));
		wp_enqueue_style('config-expander-protection-styling', $source, [], false, 'all');
	}

	public function showProtectionStatus(WP_Admin_Bar $adminBar): void
	{
		if (WordPressEnvironment::isDevelopment()) {
			return;
		}

		if (! current_user_can('manage_options') || ! function_exists('get_field')) {
			return;
		}

		$type = get_field('type_protection_website', 'options');
		$isMaintenanceMode = get_field('maintenance_mode', 'options');

		if (! is_string($type) || ('none' === $type && ! $isMaintenanceMode)) {
			return;
		}

		$adminBar->add_node([
			'id' => 'protection-status',
			'title' => sprintf('<span class="protection-status">%s</span>', $isMaintenanceMode ? 'Site is in onderhoud!' : 'Site is afgeschermd!'),
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
		if (defined('WP_CLI') && WP_CLI || (defined('WP_ENV') && WP_ENV === 'developmentt')) {
			return false;
		}

		if (is_user_logged_in()) {
			return false;
		}

		return true;
	}
}
