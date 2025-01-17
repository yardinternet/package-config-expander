<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup\Plugins;

class SearchWP extends BaseAbstract
{
	protected string $plugin = 'searchwp/index.php';

	public function cleanup(): void
	{
		if (! $this->isPluginActive($this->plugin)) {
			return;
		}

		add_filter('searchwp\background_process\use_legacy_lock', '__return_true');
	}
}
