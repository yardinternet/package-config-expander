# CHANGELOG

## v1.0.2 - 2024-08-16

- Change: login protection validation only when URI contains '/wp-admin' and '/wp-login'

## v1.0.1 - 2024-08-16

- Add: disable wp sitemaps via filter
- Change: site protection validation now only runs when the user is not logged in, ensuring that logged-in users are not unnecessarily subjected to this check.
