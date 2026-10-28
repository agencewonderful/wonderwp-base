# Native block (`core/*`) — recipe

Use when Step 2 of [`declare-block-style`](../SKILL.md) chose a `core/*` target.
Oracle: an existing remap in **this** design system whose `to.component` is the
same block — copy `to.className` / `variantScope`, do not invent names.

## Preconditions

- Variant file exists (`declare-variant` is a **separate** run if not).
- Consumer `feature/` branch is checked out.

## Remap

1. Patch the **root** `contract.<vocabulary>.remap.json` (create it only if
   none exists).
2. If a **semantic** remap already maps this atom/molecule to the core block,
   keep it. Add/update only:
   - `variations.{axis}.strategy: "block-styles"`
   - `selectors.{value}: ".is-style-{value}"`
   - `labels.{value}` in the project language
3. `to.className` **starts** with `wp-block-{block}` (the class Gutenberg
   puts on the wrapper). Keep `ds-{slug}` as a second entry if the PHP
   molecule still emits it.
4. `variantScope` only when Gutenberg applies `is-style-*` on a **wrapper**
   that is not the styled node (`core/button`: scope `wp-block-button`,
   `to.className` = `wp-block-button__link`).

## Load

```bash
node {p}/scripts/load-wonderwp.mjs --routes components-css,php,block-styles
```

Expect `{theme}/includes/BlockStyles/{PascalValue}{Block}Style.php` with
`getKey()` = `core/{block}` and `name` = `{value}`.

## CSS check

The generated component stylesheet must contain:

```css
.wp-block-{block}.is-style-{value} { … }
```

or, with `variantScope`, a descendant from the scoped wrapper. If the file
still says `.{ds-slug}.is-style-{value}` only, `to.className[0]` is wrong —
fix the remap and reload `components-css`.

Replaced content (`img` / `video` inside `core/image` / `core/video`) is
styled via descendants of the `<figure>` / wrapper, not on the replaced
element itself.

## Do not

- FrontMapper / `edit.js` — native `save()` already serializes `className`.
- Rewrite `semantic.nodeNames` for a style-only change.
- Treat the DS styleguide as editor proof.

## Verify

1. Styles panel shows the label.
2. Markup: `class="wp-block-{block} is-style-{value} …"`.
3. Visual styles apply after a hard refresh.
