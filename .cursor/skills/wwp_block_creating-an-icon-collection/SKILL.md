---
name: creating-an-icon-collection
description: >-
  Registers a WordPress 7.1 icon collection and auto-discovers SVG icons from a
  folder via a WonderWP init hook. Use when the user wants to register custom
  icons for core/icon from a directory, or manage icon collections.
metadata:
  domain: wonderwp-block-editor
---

# Creating an Icon Collection

## Goal

Register a WordPress 7.1 icon collection and auto-discover SVG icons from one or more folders via a WonderWP `init` hook, so they appear in the `core/icon` block picker.

## Rules

- **Mandatory inputs:** target plugin or theme; path(s) to icon folder(s) (relative to plugin/theme root); text domain for all `__()` calls.
- **Architectural exception:** all logic lives in the hook class — no separate `IconCollectionRegistrar` helper class.
- **Classification:** simple hook (cf. *creating-a-new-hook*). No service-delegating pattern.
- When the hook class resides in `includes/Hooks` and the plugin's autoload/manager discovers hooks there, it loads automatically — no manual WordPress registration outside the class.
- Requires WordPress 7.1+ (`function_exists('wp_register_icon_collection')`). Early return on older versions — no exception.
- **Collection slug** = second argument to `loadCollectionFromPath()` when provided; otherwise `basename($iconsDir)` — always passed through `formatSlug()`.
- **Collection label** = `__(ucfirst($collectionSlug), $textdomain)` — derived from the normalized collection slug (labels may contain uppercase; slugs may not).
- **Icon slug** = relative path from `$iconsDir` without `.svg`, `/` replaced by `-`, then normalized via `formatSlug()` (e.g. `Star_Grey.svg` → `star_grey`; `arrow-btn--red.svg` → `arrow-btn-red`).
- **Icon label** = `__(ucfirst($iconSlug), $textdomain)`.
- **Discovery:** recursive scan for `*.svg` under `$iconsDir`, sorted (`sort`), then passed through a filter.
- **Slug formatting:** `formatSlug()` enforces WordPress rules — lowercase only, `[a-z0-9_-]`, must start/end with letter or digit (trims leading/trailing `-`/`_`, collapses repeated separators).
- **Exceptions:** throw `\RuntimeException` if the directory does not exist or contains no SVG files (after filter).
- **Multi-collections:** call `loadCollectionFromPath()` multiple times from `registerIconCollection()` — one collection per folder.
- Prefer `file_path` (absolute path) over inline `content` for bundled SVG files.
- Register on the `init` hook: collection **before** individual icons.

## Workflow

```mermaid
flowchart LR
    A[Step1_CollectInputs] --> B[Step2_CreateHook]
    B --> C[Step3_DocumentAndValidate]
```

### Step 1: Collect inputs

**Do:**

1. Identify the target plugin or theme.
2. Determine the icon folder path(s) relative to the plugin/theme root (e.g. `assets/icons/rec`).
3. Confirm the text domain (e.g. `wwp-child-theme`, plugin slug).
4. Classify as **simple hook** — glob + API calls, no service delegation.

**Success criteria:** Plugin/theme, folder path(s), and text domain are confirmed.

### Step 2: Create the hook class

**Do:**

1. In the target plugin, use the *creating-a-new-hook* skill as a foundation:
   - Target the plugin and describe the purpose as "register icon collection(s) from SVG folder(s)".
2. Copy [assets/RegisterIconCollectionHook.php.tpl](assets/RegisterIconCollectionHook.php.tpl) into `includes/Hooks/`.
3. Adapt placeholders:
   - `[PluginNamespace]` — plugin namespace.
   - `[Collection]` — collection name for the class (e.g. `Rec` → `RegisterRecIconCollectionHook`).
   - `[relative/path/to/icons]` — relative path to the SVG folder.
   - `[text-domain]` — translation text domain.
   - For themes: replace `[PluginNamespace]::PATH . self::ICONS_DIR` with `get_theme_file_path(self::ICONS_DIR)`.
4. Ensure the class:
   - Implements `RegistrableInterface` and `HasHookManagerInterface`.
   - Uses the `HasHookManager` trait.
   - Registers `init` → `registerIconCollection`.
   - Delegates to `protected loadCollectionFromPath(string $iconsDir, ?string $collectionSlug = null)` for each folder.
5. For multiple collections, add multiple `loadCollectionFromPath()` calls in `registerIconCollection()`.
6. Pass an explicit `$collectionSlug` when the folder basename should not be used as the collection name (e.g. `loadCollectionFromPath($dir, 'rec')`).

**Success criteria:** Hook class exists under `includes/Hooks`, compiles, and follows the template structure.

### Step 3: Document and validate

**Documentation:**

- In the plugin's `README.md`:
  - Add or update a "Hooks" section.
  - Document the hook class, collection slug(s), folder path(s), and the `svg_files` filter hook name.

**Manual validation:**

1. Ensure SVG files exist in the target folder(s).
2. Open the block editor, insert a `core/icon` block.
3. Click **Replace** in the toolbar → verify the collection tab appears with registered icons.
4. Confirm block markup uses the expected handle: `<!-- wp:icon {"icon":"rec/graduation-cap"} /-->`.

**Proposing a commit:**

- Propose a commit summarizing the new hook class and README updates.

**Success criteria:** Collection visible in Icon Library; README documents the hook.

## Advanced Features

### Example – REC icon collection

User intent:

> "Register the `rec` icon collection from `design-system/assets/icons/rec/`."

Flow:

1. Folder: `design-system/assets/icons/` (SVG files may live in subfolders)
2. Explicit slug: `loadCollectionFromPath(get_theme_file_path('design-system/assets/icons'), 'rec')`
3. Slug / label: `rec` → `__('Rec', 'wwp-child-theme')`
4. Icon: `rec/graduation-cap.svg` or nested `line/graduation-cap.svg` → handle `rec/graduation-cap` or `rec/line-graduation-cap`
5. Create hook in theme `includes/Hooks/RegisterRecIconCollectionHook.php`.
6. Usage in blocks: `<!-- wp:icon {"icon":"rec/graduation-cap"} /-->`

### Example – Explicit collection slug

When the folder basename differs from the desired collection slug:

```php
$this->loadCollectionFromPath(
    get_theme_file_path('assets/raw/svg'),
    'rec'
);
```

Folder `svg` → collection slug `rec` → label `__('Rec', …)`.

### Example – Multiple collections

```php
public function registerIconCollection(): void
{
    if (!function_exists('wp_register_icon_collection')) {
        return;
    }

    $base = [PluginNamespace]::PATH . 'assets/icons/';
    $this->loadCollectionFromPath($base . 'rec');
    $this->loadCollectionFromPath($base . 'brand', 'brand');
}
```

Each call registers one collection. Slug defaults to the folder basename unless overridden.

### Filter – customize SVG file list

Hook consumers can filter the discovered SVG files before registration:

```php
add_filter(RegisterRecIconCollectionHook::class . '/svg_files', function (
    array $svgFiles,
    string $collectionSlug,
    string $iconsDir
): array {
    // Exclude specific icons, reorder, or append virtual entries.
    return array_values(array_filter(
        $svgFiles,
        static fn (string $path): bool => !str_ends_with($path, '/deprecated.svg')
    ));
}, 10, 3);
```

Filter signature: `apply_filters(static::class . '/svg_files', $svgFiles, $collectionSlug, $iconsDir)`.

### Alternative – manual enum approach

The [WordPress 7.1 icon tutorial](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/) uses a PHP enum with explicit cases per icon. That approach suits small, fixed icon sets. This skill's folder-scan approach suits larger or design-system-driven collections. See [references/icon-registration-api.md](references/icon-registration-api.md).

### Follow-ups

- Wire icon handles into design-system contract remaps (`rec/graduation-cap` in `core/icon` block attributes).
- Add icons to block style variations or block.json `icon` fields using registered handles.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Template | [assets/RegisterIconCollectionHook.php.tpl](assets/RegisterIconCollectionHook.php.tpl) | WonderWP hook with `loadCollectionFromPath` |
| Reference | [references/icon-registration-api.md](references/icon-registration-api.md) | WordPress 7.1 Icon Registration API summary |
| Skill | [creating-a-new-hook](../../wonderwp-backend/skills/creating-a-new-hook/SKILL.md) | Hook scaffolding foundation |
| External | [Hands-on with the WordPress 7.1 Icon Registration API](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/) | Official tutorial |
