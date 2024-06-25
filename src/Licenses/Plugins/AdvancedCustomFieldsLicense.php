<?php

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

        $licenseKey = get_option('acf_pro_license');

        if (false !== $licenseKey) {
            update_option('acf_pro_license', $this->getLicense());

            return;
        }

        add_option('acf_pro_license', $this->getLicense());
    }
}
