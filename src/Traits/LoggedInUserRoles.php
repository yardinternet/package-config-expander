<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Traits;

use WP_User;

trait LoggedInUserRoles
{
	/**
	 * Check if the currently logged-in user has a specific role.
	 */
	public function loggedInUserHasRole(string $role): bool
	{
		$roles = $this->getLoggedInUserRoles();

		return in_array($role, $roles, true);
	}

	public function getLoggedInUserRoles(): array
	{
		$user = wp_get_current_user();

		if (! $user instanceof WP_User) {
			return [];
		}

		return (array) $user->roles;
	}
}
