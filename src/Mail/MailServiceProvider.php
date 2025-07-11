<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Mail;

use Illuminate\Support\ServiceProvider;

/**
 * Register Mail ServiceProvider.
 *
 * @since 1.1.0
 */
class MailServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		/**
		 * Align the Return-Path with the From address to improve deliverability,
		 * and set the hostname for consistent Message-ID generation (per site in multisite).
		 */
		add_action('phpmailer_init', function ($phpmailer) {
			$phpmailer->Sender = $phpmailer->From;
			$hostName = parse_url(site_url(), PHP_URL_HOST);

			if (is_string($hostName) && 0 < strlen($hostName)) {
				$phpmailer->Hostname = preg_replace('/^www\./', '', $hostName);
			}
		});
	}
}
