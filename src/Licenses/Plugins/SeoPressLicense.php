<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Plugins;

use Yard\ConfigExpander\Licenses\Contracts\AbstractLicense;

class SeoPressLicense extends AbstractLicense
{
	protected const LICENSE_CONFIG_KEY = 'SEOPRESS_LICENSE_KEY';
	protected const PLUGIN_LICENSE_CONSTANT = 'SEOPRESS_LICENSE_KEY';

	public function register(): void
	{
		if (defined(self::PLUGIN_LICENSE_CONSTANT) || empty($this->getLicense())) {
			return;
		}

		define(self::PLUGIN_LICENSE_CONSTANT, $this->getLicense());
	}
}
