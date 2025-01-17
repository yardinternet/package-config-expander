<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses;

use Illuminate\Support\ServiceProvider;
use Yard\ConfigExpander\Licenses\Plugins\AdvancedCustomFieldsLicense;
use Yard\ConfigExpander\Licenses\Plugins\FacetWPLicense;
use Yard\ConfigExpander\Licenses\Plugins\GravityFormsLicense;
use Yard\ConfigExpander\Licenses\Plugins\SearchWPLicense;
use Yard\ConfigExpander\Licenses\Plugins\SeoPressLicense;
use Yard\ConfigExpander\Licenses\Plugins\WpMigrateLicense;

class LicensesServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		$this->setLicenses();
	}

	protected function setLicenses(): void
	{
		$licenses = resolve('config')->get('yard-config-expander.licenses', []);

		if (! is_array($licenses) || count($licenses) === 0) {
			return;
		}

		LicenseManager::make($licenses)->registerPlugins([
			AdvancedCustomFieldsLicense::class,
			GravityFormsLicense::class,
			FacetWPLicense::class,
			SearchWPLicense::class,
			SeoPressLicense::class,
			WpMigrateLicense::class,
		])->load();
	}
}
