# CHANGELOG

## v1.2.7 - 2025-05-09

- Fix: current screen is not available outside admin context

## v1.2.6 - 2025-05-02

- Fix: emoji scripts and styles not removed in admin

## v1.2.5 - 2025-05-02

- Change: restore main RSS feed only when setting is set to true

## v1.2.4 - 2025-04-23

- Change: remove unsetting of endpoint '/wp/v2/users/me', WP handles authentication by default
- Feat: disable admin email check

## v1.2.3 - 2025-03-26

- Fix: TypeError in removeWordPressVersion()

## v1.2.2 - 2025-03-12

- Fix: broken feed template redirect

## v1.2.1 - 2025-02-20

- Fix: extract branch name when it is actually a commit

## v1.2.0 - 2025-02-17

- Add: add no cache header and purge varnish
- Add: yard-php-cs-fixer rules
- Change: improve GH actions
- Change: update deps

## v1.1.0 - 2024-10-21

- Add: mail service provider to fix sender attribute
- Change: update deps

## v1.0.9 - 2024-10-08

- Fix: yard-y-icon.css url paths
- Change: update deps

## v1.0.8 - 2024-09-30

- Change: protect website access when user is not logged in
- Change: WpMigrateLicense retrieve Yard admin user

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
