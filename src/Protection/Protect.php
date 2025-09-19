<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

use WP_Post;

class Protect
{
	public function handleSite(): void
	{
		$this->authorizeAccess('site');
	}

	public function handleLogin(): void
	{
		$this->authorizeAccess('login');
	}

	protected function authorizeAccess(string $type): void
	{
		if ($this->maintenanceModeEnabled()) {
			$this->getMaintenancePage();
		}

		if ($this->checkIfVisitorHasAccess($type)) {
			return;
		}

		$this->denyAccess();
	}

	/**
	 * Set a custom header which can be used by proxy servers to omit caching.
	 * This is because if IP blocking is enabled there can be multiple versions of the same page: 403 and 200 for instance.
	 */
	protected function setNoCacheHeader(): void
	{
		header('Cache-Control: no-cache');
	}

	protected function checkIfVisitorHasAccess(string $type): bool
	{
		if (! in_array($type, $this->getProtectionTypesWebsite())) {
			return true;
		}

		$this->setNoCacheHeader();

		foreach ($this->getWhitelistedEntities() as $whitelistEntity) {
			if (! $this->intersectsWithProtectedTypesWebsite($whitelistEntity)) {
				continue;
			}

			if (! $this->currentProtectionTypeMatchesWhitelist($type, $whitelistEntity)) {
				continue;
			}

			if ($this->ipCurrentVisitor() !== $whitelistEntity->ipAddress()) {
				continue;
			}

			return true;
		}

		return false;
	}

	/**
	 * Validate if the whitelisted entity types intersect with the protection types of the website.
	 * Without an intersection the visitor should be denied access.
	 */
	protected function intersectsWithProtectedTypesWebsite(WhitelistEntity $whitelistEntity): bool
	{
		return ! empty(array_intersect($whitelistEntity->types(), $this->getProtectionTypesWebsite()));
	}

	/**
	 * The type which is validated should match one of the whitelisted entity protection types.
	 */
	protected function currentProtectionTypeMatchesWhitelist(string $type, WhitelistEntity $whitelistEntity): bool
	{
		return in_array($type, $whitelistEntity->types());
	}

	protected function denyAccess(): void
	{
		header('HTTP/1.0 401 Unauthorized');
		echo __('You\'re not allowed to view this page.', 'config-expander');
		exit;
	}

	protected function ipCurrentVisitor(): string
	{
		return $_SERVER['REMOTE_ADDR'] ?? '';
	}

	/**
	 * @return array<int, string>
	 */
	protected function getProtectionTypesWebsite(): array
	{
		if (! function_exists('get_field')) {
			return [];
		}

		$type = get_field('type_protection_website', 'options');

		if (! is_string($type) || 'none' === $type) {
			return [];
		}

		return 'both' === $type ? ['login', 'site', 'both'] : [$type];
	}

	/**
	 * @return WhitelistEntity[]
	 */
	protected function getWhitelistedEntities(): array
	{
		$field = get_field('ip_whistelisting', 'options');

		if (! is_array($field) || empty($field)) {
			return [];
		}

		$group = $field['ip_whistelisting_group'] ?? [];

		if (! is_array($group) || empty($group)) {
			return [];
		}

		return array_map(fn ($entity) => new WhitelistEntity($entity), $group);
	}

	protected function maintenanceModeEnabled(): bool
	{
		return function_exists('get_field') && (bool) get_field('maintenance_mode', 'options');
	}

	protected function getMaintenancePage(): void
	{
		if (! function_exists('get_field')) {
			return;
		}

		$post = get_field('maintenance_page', 'options');

		if (! $post instanceof WP_Post) {
			return;
		}

		header('HTTP/1.0 503 Service Unavailable');
		header('Retry-After: ' . DAY_IN_SECONDS);
		echo view('yard-config-expander::maintenance-page', [
			'title' => $post->post_title,
			'content' => $post->post_content,
		]);

		exit;
	}
}
