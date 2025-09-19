<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup;

use Illuminate\Support\ServiceProvider;

class CleanupServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->singleton('scripts-styles', function ($app) {
			return new EnqueuedScriptsStyles();
		});
	}

	public function boot(): void
	{
		$this->hooks();
	}

	protected function hooks(): void
	{
		add_action('wp_loaded', $this->cleanupPlugins(...), 10, 0);
		add_filter('map_meta_cap', (new MapMetaCap())->unfilteredHTML(...), 10, 4);

		add_filter('style_loader_src', resolve('scripts-styles')->removeWordPressVersion(...), 9999, 2);
		add_filter('script_loader_src', resolve('scripts-styles')->removeWordPressVersion(...), 9999, 2);
	}

	public function cleanupPlugins(): void
	{
		$plugins = resolve('config')->get('yard-config-expander.cleanup.plugins', []);

		if (! is_array($plugins)) {
			return;
		}

		foreach ($plugins as $plugin) {
			if (! class_exists($plugin)) {
				continue;
			}

			// Current class should extend the specified Abstract Class.
			if (! is_subclass_of($plugin, 'Yard\ConfigExpander\Cleanup\Plugins\BaseAbstract')) {
				continue;
			}

			(new $plugin())->cleanup();
		}
	}
}
