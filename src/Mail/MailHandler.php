<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Mail;

use Illuminate\Support\Facades\Log;

class MailHandler
{
	/**
	 * @param array<string, mixed> $args
	 *
	 * @return array<string, mixed>
	 */
	public static function filter(array $args): array
	{
		$allowedEnvironments = apply_filters('wp_mail_allowed_environments', ['dev', 'prod', 'development', 'production']);
		if (! defined('WP_ENV') || in_array(WP_ENV, $allowedEnvironments)) {
			return $args;
		}

		$config = resolve('config');
		$whitelistedEmails = $config->get('email.whitelisted_emails', []);
		if (! is_array($whitelistedEmails)) {
			$whitelistedEmails = [];
		}
		$whitelistedDomains = $config->get('email.whitelisted_domains', ['yard.nl']);
		if (! is_array($whitelistedDomains)) {
			$whitelistedDomains = [];
		}

		if (
			(! is_array($whitelistedEmails) || empty($whitelistedEmails)) &&
			(! is_array($whitelistedDomains) || empty($whitelistedDomains))
		) {
			Log::warning('Email not sent due to missing whitelisted recipients.');
			$args['to'] = [];
			$args['cc'] = [];
			$args['bcc'] = [];

			return $args;
		}

		$fields = ['to', 'cc', 'bcc'];
		$original = [];
		$nonWhitelisted = [];
		$notWhitelisted = false;

		foreach ($fields as $field) {
			$original[$field] = (array)($args[$field] ?? []);
			if ([] === $original[$field]) {
				continue;
			}
			$emails = $original[$field];
			$filteredEmails = array_values(array_filter($emails, function ($email) use ($whitelistedEmails, $whitelistedDomains): bool {
				if (in_array($email, $whitelistedEmails, true)) {
					return true;
				}

				foreach ($whitelistedDomains as $domain) {
					if (str_ends_with(strtolower((string)$email), '@' . strtolower((string)$domain))) {
						return true;
					}
				}

				return false;
			}));

			if (count($filteredEmails) !== count($emails)) {
				$notWhitelisted = true;
			}

			$nonWhitelisted[$field] = array_diff($emails, $filteredEmails);
			$args[$field] = $filteredEmails;
		}

		if ($notWhitelisted) {
			add_filter('gettext', function (string $translated, string $original, string $domain): string {
				if ('default' === $domain && '<strong>Error:</strong> The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.' === $original) {
					$translated = '<strong>Error:</strong> Email not added to whitelist.';
				}

				return $translated;
			}, 10, 3);

			Log::debug(
				sprintf(
					"Email not sent due to non-whitelisted recipients:\nTo: %s\nCc: %s\nBcc: %s",
					implode(', ', $nonWhitelisted['to']),
					implode(', ', $nonWhitelisted['cc']),
					implode(', ', $nonWhitelisted['bcc'])
				)
			);

			Log::debug(
				sprintf(
					"Email:\nHeaders: %s\nFrom: %s\nTo: %s\nCc: %s\nBcc: %s\nSubject: %s\nMessage: %s",
					implode(', ', (array)($args['headers'] ?? [])),
					$args['from'] ?? '',
					implode(', ', $original['to']),
					implode(', ', $original['cc']),
					implode(', ', $original['bcc']),
					$args['subject'] ?? '',
					$args['message'] ?? '',
				)
			);
		}

		return $args;
	}

	/**
	 * @param array<string, mixed> $args
	 *
	 * @return array<string, mixed>
	 */
	public static function prefixSubject(array $args): array
	{
		$allowedEnvironments = apply_filters('wp_mail_allowed_environments', ['dev', 'prod', 'development', 'production']);
		if (! defined('WP_ENV') || in_array(WP_ENV, $allowedEnvironments)) {
			return $args;
		}

		$args['subject'] = '[Test mail]: ' . $args['subject'];

		return $args;
	}
}
