<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Contracts;

use Yard\ConfigExpander\Support\Traits\PluginActive;

abstract class AbstractLicense implements LicenseInterface
{
	use PluginActive;

	protected const LICENSE_CONFIG_KEY = '';
	protected const PLUGIN_LICENSE_CONSTANT = '';

	/**
	 * @var array<string, string>
	 */
	protected array $licenses;

	/**
	 * @param array<string, string> $licenses
	 */
	public function __construct(array $licenses)
	{
		$this->licenses = $licenses;
	}

	abstract public function register(): void;

	protected function getLicense(): string
	{
		return $this->licenses[static::LICENSE_CONFIG_KEY] ?? '';
	}
}
