<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

use WP_Error;
use WP_REST_Request;
use WP_REST_Server;

class DisableUsersEndpoint
{
	public static function init(): void
	{
		if (is_admin()) {
			return;
		}

		add_filter('rest_endpoints', [__CLASS__, 'removeUsersEndpoints']);
		add_filter('rest_pre_dispatch', [__CLASS__, 'disableUsersView'], 10, 3);
	}

	/**
	 * Filter the registered endpoints.
	 *
	 * @param WP_REST_Request|null $result The result to send to the client. Default null to not send anything.
	 * @param WP_REST_Server $server The REST server instance.
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return mixed The filtered result.
	 */
	public static function disableUsersView(?WP_REST_Request $result, WP_REST_Server $server, WP_REST_Request $request): mixed
	{
		$method = $request->get_method();

		if (($server::READABLE === $method || 'HEAD' === $method) && preg_match('!^/wp/v2/users(?:$|/)!', $request->get_route())) {
			if (current_user_can('list_users')) {
				return $result;
			}

			return new WP_Error('rest_user_cannot_view', 'Sorry, you are not allowed to use this API.', ['status' => rest_authorization_required_code()]);
		}

		return $result;
	}

	/**
	 * Remove 'users' endpoints when not loggedin.
	 *
	 * @param array<string, array{}> $endpoints
	 *
	 * @return array<string, array{}> $endpoints
	 */
	public static function removeUsersEndpoints(array $endpoints): array
	{
		if (! is_user_logged_in() || (! current_user_can('list_users'))) {
			unset($endpoints['/wp/v2/users']);
			unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);

			return $endpoints;
		}

		return $endpoints;
	}
}
