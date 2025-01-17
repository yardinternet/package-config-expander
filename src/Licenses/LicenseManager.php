<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses;

use Exception;
use Yard\ConfigExpander\Licenses\Contracts\LicenseInterface;

class LicenseManager
{
	/**
	 * @var array<string, object>
	 */
	private array $plugins = [];

	/**
	 * @var array<string, string>
	 */
	private array $licenses = [];

	/**
	 * @param array<string, string> $licenses
	 */
	public function __construct(array $licenses)
	{
		$this->licenses = $licenses;
	}

	/**
	 * @param array<string, string> $licenses
	 */
	public static function make(array $licenses): self
	{
		return new self($licenses);
	}

	/**
	 * @param array<int, string> $plugins
	 */
	public function registerPlugins(array $plugins): self
	{
		foreach ($plugins as $plugin) {
			$this->registerPlugin($plugin);
		}

		return $this;
	}

	/**
	 * @param string $plugin
	 */
	public function registerPlugin(string $plugin): self
	{
		if (! in_array(LicenseInterface::class, class_implements($plugin) ?: [])) {
			throw new Exception(sprintf('%s is not a class which implements %s', $plugin, 'LicenseInterface'));
		}

		if (! isset($this->plugins[$plugin])) {
			$this->plugins[$plugin] = new $plugin($this->licenses);
		}

		return $this;
	}

	public function load(): void
	{
		foreach ($this->plugins as $plugin) {
			if (! method_exists($plugin, 'register')) {
				continue;
			}

			(new $plugin($this->licenses))->register();
		}
	}
}
