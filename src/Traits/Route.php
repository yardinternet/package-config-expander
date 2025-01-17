<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Traits;

use RuntimeException;

trait Route
{
	public function route(string $path): string
	{
		return $this->composeBaseURL() . trailingslashit($path);
	}

	/**
	 * Constructs the base URL of the main site, ensuring that subdirectories load
	 * their assets from the main site.
	 */
	private function composeBaseURL(): string
	{
		$fullURL = home_url();
		$components = parse_url($fullURL);

		if (! $components || ! isset($components['scheme'], $components['host'])) {
			throw new RuntimeException('Invalid URL returned by home_url()');
		}

		$baseURL = sprintf('%s://%s', $components['scheme'], $components['host']);

		if (isset($components['port'])) {
			$baseURL .= ':' . $components['port'];
		}

		return $baseURL;
	}
}
