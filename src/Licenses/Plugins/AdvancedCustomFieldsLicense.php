<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Plugins;

use Yard\ConfigExpander\Licenses\Contracts\AbstractLicense;

class AdvancedCustomFieldsLicense extends AbstractLicense
{
	protected const LICENSE_CONFIG_KEY = 'ACF_PRO_LICENSE_KEY';

	public function register(): void
	{
		if (empty($this->getLicense())) {
			return;
		}

		if (function_exists('acf_pro_update_license')) {
			acf_pro_update_license($this->getLicense());
		}

		if (function_exists('acf_pro_get_license_status')) {
			acf_pro_get_license_status(false); // Uses cached data when cache is not expired.
		}
	}
}
