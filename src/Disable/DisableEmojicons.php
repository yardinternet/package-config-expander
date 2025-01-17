<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisableEmojicons
{
	public static function init(): void
	{
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		add_filter('emoji_svg_url', '__return_false');

		// Filter to remove TinyMCE emojis.
		add_filter('tiny_mce_plugins', function ($plugins) {
			if (! is_array($plugins)) {
				return [];
			}

			return array_diff($plugins, ['wpemoji']);
		});
	}
}
