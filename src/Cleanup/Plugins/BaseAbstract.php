<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup\Plugins;

use Yard\ConfigExpander\Support\Traits\PluginActive;

abstract class BaseAbstract
{
	use PluginActive;

	protected string $plugin = '';

	abstract public function cleanup(): void;
}
