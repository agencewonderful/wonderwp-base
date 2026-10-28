# Custom block (`{ns}/{name}`) — recipe

Use when Step 2 of [`declare-block-style`](../SKILL.md) chose a non-`core/*`
block (plugin block with `render.php`, often a Query Loop post card).

## Preconditions

- Variant file exists (`declare-variant` is a **separate** run if not).
- Consumer `feature/` branch is checked out.
- Locate: block `render.php`, theme/plugin FrontMapper that instantiates the
  DS molecule, editor `edit.js` if the canvas does not go through PHP.

## Remap (new)

Root `contract.<vocabulary>.remap.json` only:

```json
{
  "remaps": ["aesthetic"],
  "to": {
    "component": "{ns}/{name}",
    "vocabulary": "wordpress-core",
    "className": ["ds-{slug}"],
    "note": "Post-load: strip WP_Block::render(); keep wrapper CSS bridge."
  },
  "tokens": {},
  "variations": {
    "{axis}": {
      "strategy": "block-styles",
      "selectors": { "{value}": ".is-style-{value}" },
      "labels": { "{value}": "<project-language label>" }
    }
  }
}
```

- **No** `semantic`. **No** `variantScope`.
- `to.component` stays so `block-styles.php` enqueues the sheet (and
  `childComponents` deps) on this block, and so `VARIANT_CLASSES` maps
  `{axis}:{value}` → `is-style-{value}`.
- If a semantic remap already exists, do not convert it to aesthetic-only
  unless that remap is what injects `WP_Block::render()` — then drop
  `semantic` / `variantScope` and keep the carnet.

## Load

```bash
node {p}/scripts/load-wonderwp.mjs --routes components-css,php,block-styles
```

Expect `{theme}/includes/BlockStyles/{PascalValue}{Block}Style.php` with
`getKey()` = `{ns}/{name}`.

## Post-load checklist (every regeneration)

1. **PHP molecule:** remove `WP_Block::render()` (and any extra wrapper)
   reintroduced because `to.component` is set.
2. **CSS bridge** — Gutenberg sets `is-style-{value}` on
   `.wp-block-{ns}-{name}`, not on `.{ds-class}`:

   ```css
   .wp-block-{ns}-{name}.is-style-{value} .{ds-class} { … }
   ```

   Keep the inner `.{ds-class}.is-style-{value}` rules if PHP also applies
   the class via the variant axis.
3. **FrontMapper:** if `attributes['className']` contains `is-style-{value}`,
   call the molecule’s variant axis (the method named after `{axis}`).
   `VARIANT_CLASSES` then emits `is-style-{value}` on the DS root.
4. **`edit.js`:** copy the wrapper’s style class onto the DS root so the
   canvas matches PHP.
5. **Enqueue:** rely on `to.component` + `childComponents`. Do not add
   manual `wp_enqueue_style` for those handles.

Write this checklist in `to.note` so a later `ds:load` is restored the same
way.

## Do not

- Add `variantScope: "wp-block-{ns}-{name}"` — second wrapper + recursion.
- Treat `VARIANT_CLASSES` as sufficient without the wrapper CSS bridge.
- Commit CSS/PHP glue that `ds:load` will wipe without documenting restore.

## Verify

1. Styles panel on `{ns}/{name}` shows the label.
2. Front: one `.wp-block-{ns}-{name}` wrapping `.{ds-class}`; no nested
   molecule root; no render loop.
3. Editor canvas matches front when the style is selected.
