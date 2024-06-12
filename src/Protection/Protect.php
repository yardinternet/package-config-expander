<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Protection;

class Protect
{
    private array $protectionTypes;

    public function __construct()
    {
        $this->protectionTypes = $this->getTypeProtectionWebsite();
    }

    protected function getTypeProtectionWebsite(): array
    {
        $type = get_field('type_protection_website', 'options');

        if (empty($type)) {
            return [];
        }

        return 'both' === $type ? ['login', 'site', 'both'] : [$type];
    }

    public function handleSite(): void
    {
        $this->authorizeAccess('site');
    }

    public function handleLogin(): void
    {
        if (! $this->isLoginPage()) {
            return;
        }

        $this->authorizeAccess('login');
    }

    protected function isLoginPage(): bool
    {
        return strpos($_SERVER['PHP_SELF'] ?? '', 'wp-login.php') !== false;
    }

    protected function authorizeAccess(string $type): void
    {
        if (! $this->checkIfVisitorHasAccess($type)) {
            $this->denyAccess();
        }
    }

    protected function checkIfVisitorHasAccess(string $type): bool
    {
        if (! in_array($type, $this->protectionTypes)) {
            return true;
        }

        foreach ($this->getWhitelistedEntities() as $whitelistEntity) {
            if (! in_array($whitelistEntity->type(), $this->protectionTypes) && $whitelistEntity->type() !== 'both') {
                continue;
            }
            if ($this->getCurrentVisitorIP() !== $whitelistEntity->ipAddress()) {
                continue;
            }

            if ($whitelistEntity->type() !== $type && $whitelistEntity->type() !== 'both') {
                continue;
            }

            return true;
        }

        return false;
    }

    protected function denyAccess(): void
    {
        header('HTTP/1.0 401 Unauthorized');
        echo 'Je hebt geen toestemming om deze pagina te bekijken.';
        exit;
    }

    protected function getCurrentVisitorIP(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

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
}
