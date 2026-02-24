<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\BranchViewer;

use DateTime;
use DateTimeZone;
use DomainException;
use InvalidArgumentException;
use LogicException;

class BranchViewer
{
	protected string $branchname;
	protected ?string $releaseInfo;
	private string $gitPath;
	private string $releasePath;

	public function __construct(string $gitPath, string $releasePath)
	{
		$this->gitPath = $gitPath;
		$this->releasePath = $releasePath;
		$this->branchname = $this->constructBranchname();
		$this->releaseInfo = $this->constructReleaseInfo();
	}

	public function getBranchname(): string
	{
		return trim($this->branchname);
	}

	public function getReleaseInfo(): ?string
	{
		return $this->releaseInfo;
	}

	protected function constructBranchname(): string
	{
		$branches = file($this->getGitDirectory(), FILE_USE_INCLUDE_PATH); // output: 'ref: refs/heads/feature/branchname'
		if (false === $branches) {
			$branches = [];
		}

		return $this->extractBranchname($branches);
	}

	protected function constructReleaseInfo(): ?string
	{
		$releases = file($this->getReleaseLog(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if (false === $releases) {
			$releases = [];
		}

		return $this->extractReleaseInfo($releases);
	}

	protected function getGitDirectory(): string
	{
		if (! file_exists($this->gitPath)) {
			throw new DomainException('Git directory does not exist');
		}

		return $this->gitPath;
	}

	protected function getReleaseLog(): string
	{
		if (! file_exists($this->releasePath)) {
			throw new DomainException('Release log does not exist');
		}

		return $this->releasePath;
	}

	/**
	 * Extracts the branchname from the array - input: 'ref: refs/heads/feature/branchname'
	 * 1) Converts the array to a string
	 * 2) Seperates the string by '/' and limits to three sections
	 * 3) Returns the last section which contains the branchname.
	 *
	 * @param string[] $branches
	 */
	private function extractBranchname(array $branches): string
	{
		$branch = $branches[0] ?? [];

		if (empty($branch)) {
			throw new LogicException('No branchname found');
		}

		if ($commit = $this->handlePossibleCommit($branch)) {
			return $commit;
		}

		$branchName = explode('/', $branch, 3);

		return $branchName[2] ?? $branchName[0];
	}

	private function handlePossibleCommit(string $branch): string
	{
		// 'ref' indicates that the branch is not a commit.
		if (strpos($branch, 'ref') !== false) {
			return '';
		}

		// Return the first 7 characters of the commit hash.
		return sprintf('%s (commit)', substr($branch, 0, 7));
	}

	/**
	 * @param array<string> $releases
	 */
	private function extractReleaseInfo(array $releases): ?string
	{
		$release = end($releases);

		if (! is_string($release) || '' === trim($release)) {
			return null;
		}

		$data = json_decode($release, true);

		if (json_last_error() !== JSON_ERROR_NONE || ! is_array($data) || ! is_string($data['created_at']) || ! is_string($data['user'])) {
			throw new InvalidArgumentException('Invalid release JSON');
		}

		$timezone = 'Europe/Amsterdam';

		if (function_exists('get_option')) {
			$timezoneOption = get_option('timezone_string', 'Europe/Amsterdam');

			if (is_string($timezoneOption)) {
				$timezone = $timezoneOption;
			}
		}

		$date = new DateTime($data['created_at']);
		$date->setTimezone(new DateTimeZone($timezone));
		$formattedDate = $date->format('d-m-Y - H:i:s');

		return sprintf(
			'Deployed on %s by %s',
			$formattedDate,
			trim($data['user'])
		);
	}
}
