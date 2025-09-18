<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Traits;

trait WordPressEnvironment
{
	public function isDevelopmentEnvironment(): bool
	{
		return $this->isEnvironmentType('development');
	}

	public function isStagingEnvironment(): bool
	{
		return $this->isEnvironmentType('staging');
	}

	public function isProductionEnvironment(): bool
	{
		return $this->isEnvironmentType('production');
	}

	protected function isEnvironmentType(string $type): bool
	{
		return defined('WP_ENV') && WP_ENV === $type;
	}
}
