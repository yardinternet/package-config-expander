<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests\BranchViewer;

use DomainException;
use InvalidArgumentException;
use LogicException;
use Yard\ConfigExpander\BranchViewer\BranchViewer;

beforeEach(function () {
	$this->validGitPath = __DIR__ . '/test_git/HEAD';
	$this->validReleasePath = __DIR__ . '/test_dep/releases_log';
	$this->invalidGitPath = __DIR__ . '/invalid_git/HEAD';
	$this->invalidReleasePath = __DIR__ . '/invalid_dep/releases_log';

	if (! file_exists(dirname($this->validGitPath))) {
		mkdir(dirname($this->validGitPath), 0777, true);
	}
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('constructBranchname throws DomainException if git directory does not exist', function () {
	expect(fn () => new BranchViewer($this->invalidGitPath, $this->invalidReleasePath))
		->toThrow(DomainException::class, 'Git directory does not exist');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('constructBranchname throws LogicException if no branch name is found', function () {
	file_put_contents($this->validGitPath, '');
	file_put_contents($this->validReleasePath, '{"created_at":"2026-02-20T15:54:49+0000","release_name":"466","user":"rivanuff","target":"chore\/deployment-info"}');

	expect(fn () => new BranchViewer($this->validGitPath, $this->validReleasePath))
		->toThrow(LogicException::class, 'No branchname found');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('getBranchname returns the branch name', function () {
	file_put_contents($this->validGitPath, 'ref: refs/heads/feature/branchname');
	file_put_contents($this->validReleasePath, '{"created_at":"2026-02-20T15:54:49+0000","release_name":"466","user":"rivanuff","target":"chore\/deployment-info"}');

	$branchViewer = new BranchViewer($this->validGitPath, $this->validReleasePath);
	expect($branchViewer->getBranchname())->toBe('feature/branchname');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('constructReleaseInfo to be null if no release is found', function () {
	file_put_contents($this->validGitPath, 'ref: refs/heads/feature/branchname');
	file_put_contents($this->validReleasePath, '');

	$branchViewer = new BranchViewer($this->validGitPath, $this->validReleasePath);
	expect($branchViewer->getReleaseInfo())->toBe(null);
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('getReleaseInfo returns InvalidArgumentException when release JSON invalid', function () {
	file_put_contents($this->validGitPath, 'ref: refs/heads/feature/branchname');
	file_put_contents($this->validReleasePath, '{"created_at"|"2026-02-20T15:54:49+0000""release_name":"466","user":"rivanuff","target":"chore\/deployment-info"}');

	expect(fn () => new BranchViewer($this->validGitPath, $this->validReleasePath))
		->toThrow(InvalidArgumentException::class, 'Invalid release JSON');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('getReleaseInfo returns the release info', function () {
	file_put_contents($this->validGitPath, 'ref: refs/heads/feature/branchname');
	file_put_contents($this->validReleasePath, '{"created_at":"2026-02-20T15:54:49+0000","release_name":"466","user":"rivanuff","target":"chore\/deployment-info"}');

	$branchViewer = new BranchViewer($this->validGitPath, $this->validReleasePath);
	expect($branchViewer->getReleaseInfo())->toBe('Deployed on 20-02-2026 - 16:54:49 by rivanuff');
});
