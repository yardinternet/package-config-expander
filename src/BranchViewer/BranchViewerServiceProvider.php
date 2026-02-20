<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\BranchViewer;

use DomainException;
use Illuminate\Support\ServiceProvider;
use LogicException;
use RuntimeException;
use WP_Admin_Bar;

class BranchViewerServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		$this->hooks();
	}

	private function hooks(): void
	{
		add_action('admin_bar_menu', $this->addBranchViewer(...), 9999, 1);
	}

	public function addBranchViewer(WP_Admin_Bar $adminBar): void
	{
		if (! current_user_can('manage_options')) {
			return;
		}

		try {
			$branch = new BranchViewer(\ABSPATH.'../../.git/HEAD', \ABSPATH.'../../../../.dep/releases_log');
			$title = sprintf('Branch: %s', $branch->getBranchname());
			$releaseInfo = $branch->getReleaseInfo();
		} catch (DomainException | LogicException | RuntimeException $e) {
			$title = $e->getMessage();
		}

		$adminBar->add_menu([
			'id' => 'yard-git-branch',
			'title' => $title,
		]);

        if ($releaseInfo) {
            $adminBar->add_menu([
                'id'     => 'yard-release-info',
                'parent' => 'yard-git-branch',
                'title'  => $releaseInfo,
            ]);
        }
	}
}
