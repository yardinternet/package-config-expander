<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\BranchViewer;

use DomainException;
use LogicException;

class BranchViewer
{
    protected string $branchname;
    private string $gitPath;

    public function __construct(string $gitPath)
    {
        $this->gitPath = $gitPath;
        $this->branchname = $this->constructBranchname();
    }

    public function getBranchname(): string
    {
        return trim($this->branchname);
    }

    protected function constructBranchname(): string
    {
        $branches = file($this->getGitDirectory(), FILE_USE_INCLUDE_PATH); // output: 'ref: refs/heads/feature/branchname'
        if (false === $branches) {
            $branches = [];
        }

        return $this->extractBranchname($branches);
    }

    protected function getGitDirectory(): string
    {
        if (! file_exists($this->gitPath)) {
            throw new DomainException('Git directory does not exist');
        }

        return $this->gitPath;
    }

    /**
     * Extracts the branchname from the array - input: 'ref: refs/heads/feature/branchname'
     * 1) Converts the array to a string
     * 2) Seperates the string by '/' and limits to three sections
     * 3) Returns the last section which contains the branchname.
     */
    private function extractBranchname(array $branches): string
    {
        $branch = $branches[0] ?? [];

        if (empty($branch)) {
            throw new LogicException('No branchname found');
        }

        $branchName = explode('/', $branch, 3);

        return $branchName[2];
    }
}
