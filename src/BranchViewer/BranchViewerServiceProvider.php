<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\BranchViewer;

use DomainException;
use Illuminate\Support\ServiceProvider;
use LogicException;
use WP_Admin_Bar;
use Yard\ConfigExpander\Traits\LoggedInUserRoles;

class BranchViewerServiceProvider extends ServiceProvider
{
	use LoggedInUserRoles;

	public function boot(): void
	{
		$this->hooks();
	}

	private function hooks(): void
	{
		add_action('admin_bar_menu', [$this, 'addBranchViewer'], 9999, 1);
	}

	public function addBranchViewer(WP_Admin_Bar $adminBar): void
	{
		if (! current_user_can('manage_options') && ! $this->isUserWhitelisted()) {
			return;
		}

		try {
			$branch = new BranchViewer(\ABSPATH.'../../.git/HEAD');
			$title = sprintf('Branch: %s', $branch->getBranchname());
		} catch (DomainException | LogicException $e) {
			$title = $e->getMessage();
		}

		$adminBar->add_menu([
			'id' => 'yard-git-branch',
			'title' => $title,
		]);
	}

	/**
	 * Checks if the current user is allowed to access the branch viewer admin bar node.
	 *
	 * A user is considered whitelisted if their role matches one of the roles
	 * provided by the `yard/config-expander/branch-viewer/whitelisted-roles` filter.
	 */
	private function isUserWhitelisted(): bool
	{
		$whitelistedRoles = apply_filters('yard/config-expander/branch-viewer/whitelisted-roles', ['administrator']);
		if (! is_array($whitelistedRoles) || 1 > count($whitelistedRoles)) {
			return false;
		}

		return (bool) array_intersect($whitelistedRoles,  $this->getLoggedInUserRoles());
	}
}
