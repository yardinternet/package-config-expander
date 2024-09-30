# CHANGELOG

## v1.0.7 - 2024-09-30

- Fix: use absolute paths instead of relative ones inside yard-y-icon.scss

## v1.0.6 - 2024-09-27

- Fix: trailingslashit $path in Route trait

## v1.0.5 - 2024-09-18

- Fix: call ACF methods on init action when validating protection types
- Add: disable protection init when WP_ENV const is development

## v1.0.4 - 2024-08-26

- Change: update admin labels
- Change: updated dependencies

## v1.0.3 - 2024-08-16

- Change: methode route() inside Route trait, ensuring subdirectories load their assets from the main site

## v1.0.2 - 2024-08-16

- Change: login protection validation only when URI contains '/wp-admin' and '/wp-login'

## v1.0.1 - 2024-08-16

- Add: disable wp sitemaps via filter
- Change: site protection validation now only runs when the user is not logged in, ensuring that logged-in users are not unnecessarily subjected to this check.
