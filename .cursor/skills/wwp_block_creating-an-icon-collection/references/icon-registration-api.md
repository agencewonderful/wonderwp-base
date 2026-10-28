# WordPress 7.1 Icon Registration API

Reference summary for the *creating-an-icon-collection* skill.

**Official tutorial:** [Hands-on with the WordPress 7.1 Icon Registration API](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/)

**Developer note:** [Registering and rendering SVG icons in WordPress 7.1](https://make.wordpress.org/core/2026/07/24/registering-and-rendering-svg-icons-in-wordpress-7-1/)

## Requirements

- WordPress 7.1+
- Registration on the `init` hook
- Register the **collection before** individual icons

## Functions

### `wp_register_icon_collection( string $slug, array $args )`

| Argument | Description |
| -------- | ----------- |
| `$slug` | Unique collection key, ideally prefixed with plugin/theme name (e.g. `rec`, `my-plugin-icons`) |
| `$args['label']` | Human-readable, internationalized label (shown in Icon Library UI) |
| `$args['description']` | Optional internationalized description |

### `wp_register_icon( string $icon_name, array $icon_properties )`

| Argument | Description |
| -------- | ----------- |
| `$icon_name` | Namespaced identifier: `{collection-slug}/{icon-slug}` (e.g. `rec/graduation-cap`) |
| `$icon_properties['label']` | Required. Internationalized human-readable label |
| `$icon_properties['content']` | Optional. Inline SVG markup string |
| `$icon_properties['file_path']` | Optional. Absolute path to an SVG file |

Provide **either** `content` **or** `file_path` — not both, not neither.

## Naming rules

Both `collection` and `icon-name` parts must:

- Be **lowercase only** (no uppercase characters)
- Start and end with a lowercase letter or digit
- Contain only lowercase letters, digits, hyphens, and underscores in between
- Not end with `-` or `_`

The skill's `formatSlug()` method normalizes file and folder names before registration (e.g. `fr_FR.svg` → `fr_fr`, `arrow-btn--red.svg` → `arrow-btn-red`).

### `wp_get_icon( string $icon_name, array $args )`

Renders an icon's SVG in PHP (outside block context). Example:

```php
echo wp_get_icon('rec/graduation-cap', ['size' => 32]);
```

### `wp_unregister_icon_collection( string $slug )`

Removes a collection and all its icons. Call on `init` at a priority later than registration.

## Block usage

Registered icons appear in the `core/icon` block picker. Block markup:

```html
<!-- wp:icon {"icon":"rec/graduation-cap"} /-->
```

## Registration order

```
init hook
  └── wp_register_icon_collection('rec', [...])
  └── wp_register_icon('rec/graduation-cap', [...])
  └── wp_register_icon('rec/bag', [...])
  └── ...
```

## File path vs inline content

| Approach | When to use |
| -------- | ----------- |
| `file_path` | SVG files bundled in plugin/theme (preferred for design-system assets) |
| `content` | One-off examples, dynamically generated SVG, or very small sets |

`file_path` must be an **absolute** path:

- Plugin: `[PluginNamespace]::PATH . 'assets/icons/rec/graduation-cap.svg'`
- Theme: `get_theme_file_path('assets/icons/rec/graduation-cap.svg')`

## Alternative – enum-based registration

The official tutorial demonstrates a PHP string-backed enum with explicit cases, `label()`, `handle()`, and `filePath()` methods, looped via `Icon::cases()`. That suits small, fixed icon sets with compile-time safety.

This skill's folder-scan approach (via `loadCollectionFromPath`) suits:

- Large icon libraries (e.g. Streamline sets)
- Design-system-driven collections where SVG files are the source of truth
- Multiple collections from sibling folders
- Recursive folder structures (nested SVG files are discovered automatically)

`loadCollectionFromPath($iconsDir, $collectionSlug = null)` accepts an optional collection slug override. Icon slugs are built from the relative path (`line/foo.svg` → `line-foo`) then normalized via `formatSlug()`.

## Known limitations (WordPress 7.1)

- SVG element allowlist may strip unsupported elements/attributes
- `stroke` attribute may be stripped
- No dedicated editor React component for custom icon pickers (icons appear in the standard Icon Library modal)

See the [official tutorial](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/#the-missing-pieces-what-you-can-t-yet-do) for details on upcoming improvements.
