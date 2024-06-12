<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\BranchViewer;

use DomainException;
use Illuminate\Support\ServiceProvider;
use LogicException;
use WP_Admin_Bar;

class BranchViewerServiceProvider extends ServiceProvider
{
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
        if (! \current_user_can('manage_options')) {
            return;
        }

        try {
            $branch = new BranchViewer(\ABSPATH.'../../.git/HEAD');
            $title = sprintf('Branch: %s', $branch->getBranchname());
        } catch (DomainException | LogicException $e) {
            $title = $e->getMessage();
        }

        $adminBar->add_menu([
            'id'    => 'yard-git-branch',
            'title' => $title,
        ]);
    }
}
