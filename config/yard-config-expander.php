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
];
