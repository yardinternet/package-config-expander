<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests\BranchViewer;

use DomainException;
use LogicException;
use Yard\ConfigExpander\BranchViewer\BranchViewer;

beforeEach(function () {
    $this->validGitPath = __DIR__ . '/test_git/HEAD';
    $this->invalidGitPath = __DIR__ . '/invalid_git/HEAD';

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
    expect(fn () => new BranchViewer($this->invalidGitPath))
        ->toThrow(DomainException::class, 'Git directory does not exist');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('constructBranchname throws LogicException if no branch name is found', function () {
    file_put_contents($this->validGitPath, '');

    expect(fn () => new BranchViewer($this->validGitPath))
        ->toThrow(LogicException::class, 'No branchname found');
});

/**
 * @runInSeparateProcess
 *
 * @preserveGlobalState disabled
 */
test('getBranchname returns the branch name', function () {
    file_put_contents($this->validGitPath, 'ref: refs/heads/feature/branchname');

    $branchViewer = new BranchViewer($this->validGitPath);
    expect($branchViewer->getBranchname())->toBe('feature/branchname');
});
