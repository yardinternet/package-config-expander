<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Disable;

class DisableRoles
{
    /**
     * Prevent the editor role of upgrading himself to administrator.
     * We are filtering the administrator role out via the unsetAdminRoleForNonAdmins, so only admins can promote non admins.
     *
     * @param array<string, array{name: string, capabilities: array<string, bool>}> $editableRoles
     *
     * @return array<string, array{name: string, capabilities: array<string, bool>}> $editableRoles
     */
    public static function unsetAdminRoleForNonAdmins(array $editableRoles): array
    {
        if (! current_user_can('manage_options') && ! empty($editableRoles['administrator'])) {
            unset($editableRoles['administrator']);
        }

        return $editableRoles;
    }
}
