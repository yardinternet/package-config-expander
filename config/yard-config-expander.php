<?php

declare(strict_types=1);

return [
    'providers' => [
        'Yard\ConfigExpander\ACF\ACFServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\BranchViewer\BranchViewerServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\Protection\ProtectionServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\Disable\DisableServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\Cleanup\CleanupServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\Licenses\LicensesServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\LoginScreenCustomization\LoginScreenCustomizationServiceProvider' => [
            'enabled' => true,
        ],
        'Yard\ConfigExpander\Mail\MailServiceProvider' => [
            'enabled' => true,
        ],
    ],
    'defaults' => [
        'admin' => [
            'AUTOMATIC_UPDATER_DISABLED' => true,
            'AUTOSAVE_INTERVAL' => 900,
            'DISABLE_COMMENTS' => true,
            'DISABLE_POSTS' => true,
            'DISALLOW_FILE_EDIT' => true,
            'DISABLE_ADMIN_NOTICES_FOR_NON_ADMINS' => true,
            'UNSET_ADMIN_ROLE_FOR_NON_ADMINS' => true,
            'WP_CACHE' => false,
        ],
        'api' => [
            'DISABLE_REST_API_USERS' => true,
            'DISABLE_REST_API_OEMBED' => true,
        ],
        'public' => [
            'FEEDS_ENABLED' => false,
            'XMLRPC_ENABLED' => false,
            'XMLRPC_ALLOWED_METHODS' => [],
            'CLEANUP_HEADERS' => true,
            'DISABLE_EMOJICONS' => true,
        ],
    ],
    'cleanup' => [
        'plugins' => [
            Yard\ConfigExpander\Cleanup\Plugins\Stream::class,
            Yard\ConfigExpander\Cleanup\Plugins\SearchWP::class,
        ],
    ],
    'licenses' => [
        'ACF_PRO_LICENSE_KEY' => $_SERVER['ACF_PRO_LICENSE_KEY'] ?? null,
        'FACETWP_LICENSE_KEY' => $_SERVER['FACETWP_LICENSE_KEY'] ?? null,
        'GF_LICENSE_KEY' => $_SERVER['GF_LICENSE_KEY'] ?? null,
        'SEARCHWP_LICENSE_KEY' => $_SERVER['SEARCHWP_LICENSE_KEY'] ?? null,
        'SEOPRESS_LICENSE_KEY' => $_SERVER['SEOPRESS_LICENSE_KEY'] ?? null,
        'WPMDB_LICENSE_KEY' => $_SERVER['WPMDB_LICENSE_KEY'] ?? null,
    ],
];
