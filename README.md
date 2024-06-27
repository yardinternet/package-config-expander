# Yard Config Expander

[![Code Style](https://github.com/yardinternet/package-config-expander/actions/workflows/format-php.yml/badge.svg?no-cache)](https://github.com/yardinternet/package-config-expander/actions/workflows/format-php.yml)
[![PHPStan](https://github.com/yardinternet/package-config-expander/actions/workflows/phpstan.yml/badge.svg?no-cache)](https://github.com/yardinternet/package-config-expander/actions/workflows/phpstan.yml)
[![Tests](https://github.com/yardinternet/package-config-expander/actions/workflows/run-tests.yml/badge.svg?no-cache)](https://github.com/yardinternet/package-config-expander/actions/workflows/run-tests.yml)
![Code Coverage Badge](https://github.com/yardinternet/package-config-expander/blob/badges/coverage.svg)
![Lines of Code Badge](https://github.com/yardinternet/package-config-expander/blob/badges/lines-of-code.svg)

This repository provides a scaffold for creating an Acorn package. For more detailed information, please refer to the [Acorn Package Development](https://roots.io/acorn/docs/package-development/) documentation.

## Installation

To install this package using Composer, follow these steps:

1. Add the following to the `repositories` section of your `composer.json`:

    ```json
    {
      "type": "vcs",
      "url": "git@github.com:yardinternet/package-config-expander.git"
    }
    ```

2. Add the following to the `require` section of your `composer.json`:

    ```json
    {
      "yard/config-expander": "*"
    }
    ```

3. Run the following command to install the package:

    ```sh
    composer update
    ```

You can publish the config file with:

```shell
wp acorn vendor:publish --provider="Yard\ConfigExpander\ConfigExpanderServiceProvider"
```

## Configuration

After the configuration file has been published, you can customize the package settings by overwriting them.
The location of the published configuration file is: 'web/app/themes/{theme-name}/config/yard-config-expander.php

### Calling Service Providers

Service providers are defined in a configuration file `yard-config-expander.php`. Each provider has an `enabled` flag to indicate whether it should be invoked.

#### Example Configuration for Service Providers

```php
$config = [
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
  ]
];
```

### Settings

Additional settings are also defined in the same configuration file `yard-config-expander.php`.
These settings can be customized to fit your specific needs.

```php
$config = [
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
```

### Plugin defaults

Considering the following section of the configuration file `yard-config-expander.php`:

```php
$config = [
  'cleanup' => [
    'plugins' => [
      Yard\ConfigExpander\Cleanup\Plugins\Stream::class,
      Yard\ConfigExpander\Cleanup\Plugins\SearchWP::class
    ]
  ]
]
```

The provided classes are designed to manage the settings and cleanup tasks for various plugins within a WordPress environment.
These classes must extend the common base class `BaseAbstract` and include methods to handle both single-site and multi-site configurations.

## Hooks

The hooks are divided by sections based on the package directories.

### Clean-up

#### Allow 'unfiltered_html' capability for administrators, superusers and editors only

```php
add_filter('yard::config-expander/cleanup/allow-unfiltered-html', '__return_true');
```

### Login screen customization

#### Overwrite the stylesheet file (URL)

```php
add_filter('yard::config-expander/login/style-file', function(string $source) {
  return $source;
}, 10, 1);
```

#### Overwrite the logo above the login form (URL)

```php
add_filter('yard::config-expander/login/logo', function(string $logo){
  return $logo;
}, 10, 1);
```

#### Customize the URL of the anchor around the logo

```php
add_filter('yard::config-expander/login/logo-url', function(string $logo){
  return $logo;
}, 10, 1);
```

#### Overwrite the title of the link (value inside the anchor)

```php
add_filter('yard::config-expander/login/logo-name', function(string $logo){
  return $logo;
}, 10, 1);
```
