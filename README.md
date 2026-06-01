# kaiseki/wp-html-attributes

Fluent builder for composing, merging and rendering escaped HTML element attributes in WordPress.

Attributes are collected through a small chainable API, classes are merged and de-duplicated rather
than overwritten, and the rendered output is escaped with WordPress' own `esc_attr()` / `esc_url()`
and emitted in a stable, predictable order.

## Installation

```bash
composer require kaiseki/wp-html-attributes
```

Requires PHP 8.2 or newer.

## Usage

```php
use Kaiseki\WordPress\HtmlAttributes\HtmlAttributes;

$attributes = HtmlAttributes::create(['id' => 'cta'])
    ->addClass('button', 'button--primary')
    ->addAttribute('href', 'https://example.com')
    ->addAttribute('target', '_blank');

echo '<a ' . $attributes->renderAttributes() . '>Read more</a>';
// <a id="cta" href="https://example.com" target="_blank" class="button button--primary">Read more</a>
```

### Building attributes

- `HtmlAttributes::create(?array $attributes = [])` / `new HtmlAttributes(?array $attributes = [])` —
  start from an optional `name => value` map.
- `addAttribute(string $name, string $value, bool $merge = true)` — set a single attribute. With
  `$merge` (the default) a `class` value is merged into the existing classes; pass `false` to replace.
- `addAttributes(array $attributes, bool $merge = true)` — set several at once.
- `addClass(string ...$class)` — add one or more class names; each argument may itself be a
  space-separated list. Duplicates are removed on render.
- `getAttribute(string $name)` / `getAttributes()` — read back the current state.

### Rendering

`renderAttributes(array $attributes = [])` returns the escaped attribute string (any attributes
passed are merged in first):

- `href` values are escaped with `esc_url()` (the `fb-messenger` protocol is allowed in addition to
  WordPress' defaults); every other value is escaped with `esc_attr()`.
- Empty `href`, `class`, `target` and `rel` values are omitted.
- Output is ordered `id`, `href`, `target`, `rel`, `class`, then any remaining attributes alphabetically.

### Reusing the builder in your own class

The behaviour lives in `HtmlAttributesTrait`, so a class that maintains its own
`array<string, string> $attributes` can `use HtmlAttributesTrait;` to gain the same API.

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
```

## License

MIT — see [LICENSE](LICENSE.md).
