<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Contracts;

interface LicenseInterface
{
    public function register(): void;
}
