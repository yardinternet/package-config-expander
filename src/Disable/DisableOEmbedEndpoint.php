<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisableOEmbedEndpoint
{
	public static function init(): void
	{
		if (! \is_user_logged_in() || ! current_user_can('list_users')) { // Maybe alter 'list_users'?
			\remove_action('rest_api_init', 'wp_oembed_register_route');
		}
	}
}
