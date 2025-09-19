<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\ACF\Controllers;

class WhitelistGroupController
{
	protected string $groupName = 'ip_whistelisting';
	protected string $repeaterName = 'ip_whistelisting_group';

	/** @var array<int, array<string, string>> */
	protected array $defaults = [
		[
			'whitelisted_ip_address' => '185.90.186.154',
			'whitelisted_description' => 'Yard Kantoor',
			'type' => 'both',
		],
		[
			'whitelisted_ip_address' => '2a00:1fb8:ffff:21::2',
			'whitelisted_description' => 'Yard VPN',
			'type' => 'both',
		],
	];

	/**
	 * Make sure the default whitelist entries are always present in ACF Options.
	 */
	public function forceDefaults(): void
	{
		['group' => $group, 'rows' => $rows] = $this->getGroupWithRows();

		$this->addDefaultsWhenAbsent($rows, $this->mapExistingIps($rows));

		$group[$this->repeaterName] = array_values($rows);

		// @phpstan-ignore-next-line
		update_field($this->groupName, $group, 'option');

		$this->setDefaultsReadonly();
	}

	/**
	 * Sets the default whitelist entries as read-only in the ACF admin interface,
	 * except for the protection type field.
	 */
	protected function setDefaultsReadonly(): void
	{
		$makeReadonly = function ($field, string $key) {
			$values = array_column($this->defaults, $key);

			if (isset($field['value']) && in_array($field['value'], $values, true)) {
				$field['readonly'] = true;
			}

			return $field;
		};

		add_filter('acf/prepare_field/name=whitelisted_ip_address', fn ($field) => $makeReadonly($field, 'whitelisted_ip_address'));
		add_filter('acf/prepare_field/name=whitelisted_description', fn ($field) => $makeReadonly($field, 'whitelisted_description'));
	}

	/**
	 * @return array<string, array<int, array<string, string>>>
	 */
	protected function getGroupWithRows(): array
	{
		// @phpstan-ignore-next-line
		$group = get_field($this->groupName, 'option');

		if (! is_array($group)) {
			$group = [];
		}

		$rows = $group[$this->repeaterName] ?? [];

		if (! is_array($rows)) {
			$rows = [];
		}

		return ['group' => $group, 'rows' => $rows];
	}

	/**
	 * @param array<int, array<string, string>> $rows
	 *
	 * @return array<string, int> Map of normalized IP to its index in the rows array.
	 */
	protected function mapExistingIps(array $rows): array
	{
		$existingIps = [];

		foreach ($rows as $index => $row) {
			$ip = $this->normalizeIp($row['whitelisted_ip_address'] ?? '');

			if ('' !== $ip && ! isset($existingIps[$ip])) {
				$existingIps[$ip] = $index;
			}
		}

		return $existingIps;
	}

	/**
	 * @param array<int, array<string, string>> $rows
	 * @param array<string, int> $existingIps
	 */
	protected function addDefaultsWhenAbsent(array &$rows, array $existingIps): void
	{
		foreach ($this->defaults as $defaultRow) {
			$ip = $this->normalizeIp($defaultRow['whitelisted_ip_address']);

			if ('' === $ip) {
				continue;
			}

			if (! isset($existingIps[$ip])) {
				array_unshift($rows, $defaultRow); // Current default is missing, add to the start of the list.
			}
		}
	}

	protected function normalizeIp(string $ip): string
	{
		return strtolower(trim($ip));
	}
}
