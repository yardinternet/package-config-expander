<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Licenses\Plugins;

use Yard\ConfigExpander\Licenses\Contracts\AbstractLicense;

class GravitySMTPLicense extends AbstractLicense
{
	protected const GRAVITYSMTP_GENERIC_HOST = 'GRAVITYSMTP_GENERIC_HOST';
	protected const GRAVITYSMTP_GENERIC_PORT = 'GRAVITYSMTP_GENERIC_PORT';
	protected const GRAVITYSMTP_GENERIC_ENCRYPTION_TYPE = 'GRAVITYSMTP_GENERIC_ENCRYPTION_TYPE';
	protected const GRAVITYSMTP_GENERIC_AUTH = 'GRAVITYSMTP_GENERIC_AUTH';
	protected const GRAVITYSMTP_GENERIC_FORCE_FROM_EMAIL = 'GRAVITYSMTP_GENERIC_FORCE_FROM_EMAIL';

	public function register(): void
	{
		$smtpConstants = [
			self::GRAVITYSMTP_GENERIC_HOST,
			self::GRAVITYSMTP_GENERIC_PORT,
			self::GRAVITYSMTP_GENERIC_ENCRYPTION_TYPE,
			self::GRAVITYSMTP_GENERIC_AUTH,
			self::GRAVITYSMTP_GENERIC_FORCE_FROM_EMAIL,
		];

		foreach ($smtpConstants as $constant) {
			$value = $this->getLicense($constant);

			if (defined($constant) || ('' === $value)) {
				continue;
			}

			define($constant, $value);
		}
	}
}
