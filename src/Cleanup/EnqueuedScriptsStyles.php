<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup;

class EnqueuedScriptsStyles
{
	/**
	 * Remove WordPress version from enqueued scripts and styles.
	 */
	public function removeWordPressVersion(string $src, string $handle): string
	{
		if (strpos($src, 'ver=' . get_bloginfo('version'))) {
			$src = remove_query_arg('ver', $src);
		}

		return $src;
	}
}
