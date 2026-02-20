<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests\BranchViewer;

use Mockery;
use WP_Admin_Bar;
use Yard\ConfigExpander\BranchViewer\BranchViewerServiceProvider;

beforeEach(function () {
	$this->app->instance('path.public', __DIR__);
	$this->validGitPath = __DIR__ . '/test_git/HEAD';
	$this->validReleasePath = __DIR__ . '/test_dep/release_log';

	if (! file_exists(dirname($this->validGitPath))) {
		mkdir(dirname($this->validGitPath), 0777, true);
	}

	if (! file_exists(dirname($this->validReleasePath))) {
		mkdir(dirname($this->validReleasePath), 0777, true);
	}
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('addBranchViewer does nothing if user cannot manage options', function () {
	setCurrentUserCanMock(false);

	$provider = new BranchViewerServiceProvider($this->app);

	$adminBar = Mockery::mock(WP_Admin_Bar::class);
	$adminBar->shouldNotReceive('add_menu');

	$provider->addBranchViewer($adminBar);
});
