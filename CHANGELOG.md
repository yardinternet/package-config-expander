# CHANGELOG

## [v1.5.1] - 2026-06-04

Chore: add github release workflow
Fix: unrecognized DateTimeZone when timezone_string option is empty
Fix: disable admin email check
Fix: styling for new 2fa plugin and remove wordfence specific styling

## [v1.5.0] - 2026-05-08

- Feat: add option to show visitor IP on unauthorized visit
- Feat: clarify error message for unauthorized page views

## [v1.4.2] - 2026-02-24

- Fix: make release log optional

## [v1.4.1] - 2026-02-24

- Feat: show release info in git branch sub menu
- Feat: add GravitySMTP vars registration
- Chore: update to PHP 8.2
- Chore: open source with plain text license

## [v1.4.0] - 2025-09-29

- Fix: checks wrong capability for oembed

## [v1.3.0] - 2025-09-23

- Change: disable maintenance page on admin & login routes
- Change: use first callable syntax where applicable
- Change: maintenance mode does not rely on protection of website
- Feat: disable front-end protection when in production
- Feat: force default IP whitelist entries in ACF options page
- Feat: implement PHPStan acf-pro-stubs

## [v1.2.1]7 - 2025-09-15

- Refactor: rename under construction to maintenance

## [v1.2.1]6 - 2025-08-26

- Feat: Don't protection notice bar in development

## [v1.2.1]5 - 2025-08-25

- Fix: Change the way under construction page is returned to avoid raw output of laravel headers

## [v1.2.1]4 - 2025-08-25

- Chore: rename views publish tag

## [v1.2.1]3 - 2025-08-25

- Feat: publish command for views

## [v1.2.1]2 - 2025-08-25

- Feat: under construction page selector
- Feat: admin notice showing protection active
- Change: enable protection on accept and production

## [v1.2.1]1 - 2025-08-15

- Fix: styling for WordFence 2FA

## [v1.2.1]0 - 2025-07-21

- Fix: PHPStan issues
- Fix: phpmailer properties (hostname)

## [v1.2.9] - 2025-05-27

- Fix: enable protection only on accept

## [v1.2.8] - 2025-05-09

- Fix: remove emoji detection script from embeds

## [v1.2.7] - 2025-05-09

- Fix: current screen is not available outside admin context

## [v1.2.6] - 2025-05-02

- Fix: emoji scripts and styles not removed in admin

## [v1.2.5] - 2025-05-02

- Change: restore main RSS feed only when setting is set to true

## [v1.2.4] - 2025-04-23

- Change: remove unsetting of endpoint '/wp/v2/users/me', WP handles authentication by default
- Feat: disable admin email check

## [v1.2.3] - 2025-03-26

- Fix: TypeError in removeWordPressVersion()

## [v1.2.2] - 2025-03-12

- Fix: broken feed template redirect

## [v1.2.1] - 2025-02-20

- Fix: extract branch name when it is actually a commit

## [v1.2.0] - 2025-02-17

- Add: add no cache header and purge varnish
- Add: yard-php-cs-fixer rules
- Change: improve GH actions
- Change: update deps

## [v1.1.0] - 2024-10-21

- Add: mail service provider to fix sender attribute
- Change: update deps

## [v1.0.9] - 2024-10-08

- Fix: yard-y-icon.css url paths
- Change: update deps

## [v1.0.8] - 2024-09-30

- Change: protect website access when user is not logged in
- Change: WpMigrateLicense retrieve Yard admin user

## [v1.0.7] - 2024-09-30

- Fix: use absolute paths instead of relative ones inside yard-y-icon.scss

## [v1.0.6] - 2024-09-27

- Fix: trailingslashit $path in Route trait

## [v1.0.5] - 2024-09-18

- Fix: call ACF methods on init action when validating protection types
- Add: disable protection init when WP_ENV const is development

## [v1.0.4] - 2024-08-26

- Change: update admin labels
- Change: updated dependencies

## [v1.0.3] - 2024-08-16

- Change: methode route() inside Route trait, ensuring subdirectories load their assets from the main site

## [v1.0.2] - 2024-08-16

- Change: login protection validation only when URI contains '/wp-admin' and '/wp-login'

## [v1.0.1] - 2024-08-16

- Add: disable wp sitemaps via filter
- Change: site protection validation now only runs when the user is not logged in, ensuring that logged-in users are not unnecessarily subjected to this check.
