<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

require_once __DIR__ . '/Setup.php';

class TestCase extends Orchestra
{
	protected function getPackageProviders($app)
	{
		return [
			// LaravelDataServiceProvider::class,
		];
	}

	protected function setUp(): void
	{
		parent::setUp();
		setCurrentUserCanMock(false); // Default value
	}
}
