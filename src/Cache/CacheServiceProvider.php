<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cache;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		$this->hooks();
	}

	protected function hooks(): void
	{
		add_action('send_headers', [$this, 'checkCacheBypassBlocks'], 10, 0);
	}

	/**
	 * Checks whether current page contains a block which should invoke no-cache headers
	 */
	public function checkCacheBypassBlocks(): void
	{
		$bypassBlocks = resolve('config')->get('yard-config-expander.cache.bypass.blocks', []);

		if (! empty($bypassBlocks) && is_singular() && ($postId = get_queried_object_id())) {
			// Refactor to array_any when upgrading to PHP 8.4
			if (is_array($bypassBlocks) && ! empty(array_filter($bypassBlocks, fn ($blockName) => is_string($blockName) && has_block($blockName, $postId)))) {
				nocache_headers();
			}
		}
	}
}
