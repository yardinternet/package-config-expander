<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Cleanup;

use WP_User;

class MapMetaCap
{
    /**
     * Allows 'unfiltered_html' capability for admins, superusers and editors.
     *
     * @param array<int, string> $caps
     * @param string $cap
     * @param int $userID
     * @param array<mixed, mixed> $args
     *
     * @return array<mixed, string> $caps
     */
    public function unfilteredHTML(array $caps, string $cap, int $userID, array $args): array
    {
        $allow = (bool) apply_filters('yard::config-expander/cleanup/allow-unfiltered-html', false);

        if (true !== $allow) {
            return $caps;
        }

        if ('unfiltered_html' !== $cap || ! is_multisite()) {
            return $caps;
        }

        $userRoles = (new WP_User($userID))->roles;
        $userRole = array_shift($userRoles);

        if (! in_array($userRole, ['administrator', 'superuser', 'editor'])) {
            return $caps;
        }

        if (false !== ($key = $this->keyOfDoNotAllow($caps))) {
            $caps[$key] = 'unfiltered_html'; // Overwrite the 'do_not_allow' capability with 'unfiltered_html'.
        }

        return $caps;
    }

    /**
     * Retrieves the key of the 'do_not_allow' capability inside the given array.
     *
     * @param array<int, string> $caps
     *
     * @return mixed
     */
    protected function keyOfDoNotAllow(array $caps): mixed
    {
        return array_search('do_not_allow', $caps);
    }
}
