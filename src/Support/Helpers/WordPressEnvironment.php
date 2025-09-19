<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Support\Helpers;

final class WordPressEnvironment
{
    public static function isDevelopment(): bool
    {
        return self::isType('development');
    }

    public static function isStaging(): bool
    {
        return self::isType('staging');
    }

    public static function isProduction(): bool
    {
        return self::isType('production');
    }

    private static function isType(string $type): bool
    {
        return defined('WP_ENV') && WP_ENV === $type;
    }
}
