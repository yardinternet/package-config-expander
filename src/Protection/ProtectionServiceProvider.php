<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use Illuminate\Support\ServiceProvider;

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
