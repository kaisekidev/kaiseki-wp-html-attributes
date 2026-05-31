# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-05-31

First tagged release.

### Added

- `Kaiseki\WordPress\HtmlAttributes\HtmlAttributes` and `HtmlAttributesTrait` — a fluent builder for
  composing, merging and rendering escaped HTML element attributes (`addAttribute()`,
  `addAttributes()`, `addClass()`, `getAttribute()`, `getAttributes()`, `renderAttributes()`).

### Changed

- PHP requirement is `^8.2` (PHP 8.4 is the primary target).
- Modernized the dev toolchain (PHPStan 2, PHPUnit 11 schema, composer-require-checker 4) and depend
  on `kaiseki/php-coding-standard: ^1.0` with the shared PHPStan config; `kaiseki/config` and
  `kaiseki/wp-hook` pinned to `^2.0`. CI now runs via the reusable workflow in `kaisekidev/.github`.
