<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

/**
 * Mirrors a deliberate block onto the site's public flag, so `wp site list`
 * tells a site that is blocked on purpose from one that is simply down.
 */
class PublicFlag
{
	private const RESTORE_OPTION = 'yard_config_expander_blog_public';

	public static function reconcile(bool $blocked): void
	{
		if ($blocked) {
			self::hide();

			return;
		}

		self::reveal();
	}

	private static function hide(): void
	{
		// The remembered value doubles as the record that the flag is ours.
		if (false !== get_option(self::RESTORE_OPTION)) {
			return;
		}

		update_option(self::RESTORE_OPTION, self::flag());
		self::write(0);
	}

	private static function reveal(): void
	{
		$remembered = get_option(self::RESTORE_OPTION);

		if (! is_numeric($remembered)) {
			return;
		}

		delete_option(self::RESTORE_OPTION);

		// A flag raised by hand since we lowered it is theirs, not ours to overwrite.
		if (0 === self::flag()) {
			self::write((int) $remembered);
		}
	}

	/**
	 * On multisite the site row is read rather than the option, which a
	 * pre_option_blog_public filter can answer for. A single site has no such
	 * row, so there the filtered value is all there is.
	 */
	private static function flag(): int
	{
		if (! is_multisite()) {
			$stored = get_option('blog_public', 1);

			return is_numeric($stored) ? (int) $stored : 1;
		}

		return (int) get_blog_status(get_current_blog_id(), 'public');
	}

	/**
	 * update_blog_status() writes the site row itself, and core syncs that back
	 * onto blog_public. update_option() would compare against the filtered value
	 * first and bail out before writing.
	 */
	private static function write(int $value): void
	{
		if (! is_multisite()) {
			update_option('blog_public', $value);

			return;
		}

		update_blog_status(get_current_blog_id(), 'public', (string) $value);
	}
}
