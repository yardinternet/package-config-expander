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
