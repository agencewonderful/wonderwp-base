# Traps when declaring a block style from a variant

Project-agnostic. Placeholders: `{slug}`, `{axis}/{value}`, `{ns}/{name}`, `{ds}`, `{theme}`.

## Root remap, not variant-folder remap

`register_block_style()` is driven by the **root**
`contract.<vocabulary>.remap.json` carnet `variations.{axis}`. Tokens stay in
`{axis}/{value}/contract.json`. A remap inside the variant folder is for a
**pattern** (cloned composition), not for a Styles-panel entry.

## Axis name vs Gutenberg class

A `style` axis already uses `com.wonderwp.selector: ".is-style-{value}"` —
Gutenberg and the DS share the class. Any other axis (e.g. `background`)
keeps `.is-{axis}-{value}` on the variant file. Gutenberg always emits
`is-style-{value}`. Align CSS to `.is-style-{value}`; do not add a `tokens`
remap for that.

## `R-REMAP-8` — recorded is not applied

The remap engine validates the carnet and prints `RECORDED, NOT EXECUTED`. It
does not rewrite variant selectors or CSS. After writing the carnet, this
skill must make the generated CSS (and `VARIANT_CLASSES`) match
`.is-style-{value}`.

## Picker without visual styles

A generated `{Value}{Block}Style.php` only proves `register_block_style()`
ran. If `to.className[0]` is still `ds-{slug}`, CSS targets a class the
native block never emits. If the custom wrapper is unbridged, Gutenberg puts
`is-style-*` on `.wp-block-{ns}-{name}` while rules sit on `.{ds-class}`.
Verify **picker and visuals**.

## Native hook is `to.className[0]`

The CSS generator prefixes rules with the first `to.className` entry. For
`core/{block}` that must be `wp-block-{block}` (keep `ds-*` second).
`variantScope` is only for wrapper-vs-inner splits (`core/button`:
`is-style-*` on `.wp-block-button`, paint on `.wp-block-button__link`).

## `variantScope` on a custom `render.php` block

`generate-php-components` emits `WP_Block::render()` when `to.component` is
set; `variantScope` adds a second wrapper. The block’s own `render.php`
re-enters → recursion, nested `.{ds-class} .{ds-class}`, broken child
selectors. Custom Query Loop / PHP-rendered cards: **no** `variantScope`,
**no** `semantic` remap.

## Semantic remap on a block that already renders the molecule

`remaps: ["semantic"]` rewrites the tree. Combined with `to.component` it
injects `WP_Block::render()` into `getMarkup()`. Use `remaps: ["aesthetic"]`
and `tokens: {}` for a new remap. Keep `to.component` for enqueue and
`VARIANT_CLASSES` only. Do not strip `semantic` off an existing native
remap — patch `variations` + `to.className` instead.

## `VARIANT_CLASSES` is not the editor path

The PHP table (from `variations.*.selectors`) makes
`$molecule->{axis}('{value}')` emit `is-style-{value}` instead of
`is-{axis}-{value}`. Gutenberg still puts the class on the **block wrapper**.
Custom cards need FrontMapper (read `className` → call the axis) **and** a
descendant CSS bridge.

## Load overwrites custom glue

`ds:load` regenerates molecule PHP and CSS. It reintroduces `WP_Block::render()`
whenever `to.component` is set, and can wipe a hand-written wrapper bridge.
Document the checklist in `to.note`; restore after every load.

## Generator only registers `.is-style-{value}`

`generate-block-styles.mjs` skips any other selector (`.is-size-*`,
`.is-icon-*`, projections onto a core style such as `.is-style-outline`).
Those stay pattern/static classes unless registered by hand — out of scope
for this skill.

## Isolated routes, isolated PRs

A broken remap on **another** component can fail the whole `block-styles`
route. Fix or skip it; do not ship that change in the style PR. `{ds}/preview.html`
composes default axes for the styleguide — it is not the editor.
