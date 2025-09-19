<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\ACF\Controllers;

use Yard\ConfigExpander\Support\Helpers\WordPressEnvironment;

class ProtectionTypeController
{
	protected string $fieldName = 'type_protection_website';

	/** @var string[] */
	protected array $nonProductionAllowedValues = ['site', 'both'];

	/**
	 * Disable frontend protection in production environments.
	 * Maintenance mode should be used for the frontend.
	 */
	public function disableFrontendProtection(): void
	{
		if (! WordPressEnvironment::isProduction()) {
			return;
		}

		// @phpstan-ignore-next-line
		$protectionType = get_field($this->fieldName, 'option') ?: '';

		if (! is_string($protectionType) || '' === $protectionType) {
			return;
		}

		if (in_array($protectionType, $this->nonProductionAllowedValues)) {
			$protectionType = 'none';
		}

		// @phpstan-ignore-next-line
		update_field($this->fieldName, $protectionType, 'option');
	}
}
