<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Plugins;

use Yard\ConfigExpander\Licenses\Contracts\AbstractLicense;

class GravityFormsLicense extends AbstractLicense
{
    protected const LICENSE_CONFIG_KEY = 'GF_LICENSE_KEY';

    public function register(): void
    {
        if (empty($this->getLicense())) {
            return;
        }

        $licenseKey = get_option('rg_gforms_key');

        if (false !== $licenseKey) {
            update_option('rg_gforms_key', $this->getLicense());

            return;
        }

        add_option('rg_gforms_key', $this->getLicense());
    }
}
