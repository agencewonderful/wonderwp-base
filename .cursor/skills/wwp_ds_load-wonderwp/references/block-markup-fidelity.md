# Block-markup fidelity — curated templates and editor caveats

The semantic → Gutenberg serializer (`walk-block-markup.mjs`, spec in
`rules/mapping-format.md` § Block-markup section) emits **static** block
markup. Front-end rendering through `do_blocks()` is tolerant; the **editor**
is the risky surface: a static block validates by comparing the stored inner
HTML against the block's `save()` output, and any divergence triggers
"unexpected or invalid content" when the markup is opened in the editor
(iteration 2: patterns).

## The curated set (reference `to-block-markup.json`)

Each `blockMarkup.blocks` template is hand-maintained to mirror the core
block's save output — this is the fidelity surface:

| Block | Template mirrors | Notes |
|---|---|---|
| `core/button` | `div.wp-block-button > a.wp-block-button__link.wp-element-button` | `{dsClass}` + runtime classes land on the **link** so the variant CSS (`.ds-button.is-style-*`) matches one element. The editor puts `is-style-*` on the wrapper — known v1 divergence, resolved by the grouped selector planned for iteration 2 block styles. |
| `core/heading` | `h2.wp-block-heading` | Level is fixed at `h2` in v1 — the `level` axis is a `NEEDS DECISION` carried from the transform vocabulary. |
| `core/group` | `<{tagName}>.wp-block-group` | `tagName` comes from the nodeName entry (`Section` → `section`, …) and is also emitted in the attrs JSON. Never emit `layout` attrs — `is-layout-*` classes are render-time. |
| `core/image` | `figure.wp-block-image > img` | `src` / `alt` flow through `{attrsSeam}` (inline passthrough rules). |
| `core/paragraph`, `core/list`, `core/list-item`, `core/separator` | canonical save output | — |
| `"*"` fallback | plain `div` | Use is surfaced: *"editor fidelity not guaranteed"*. Front-end safe, editor-unsafe. |

## Caveats to keep in mind

- **`core/button` expects a `core/buttons` parent** in editor context.
  Standalone `do_blocks()` renders fine; pattern bodies (iteration 2) will
  need a `wrap` hint in the vocabulary. Surfaced, never guessed.
- **RichText can strip inner spans** (`ds-button__label`, `ds-button__arrow`)
  when a user edits text in the editor — RichText keeps only registered
  formats. Mitigation when that matters: also target structural selectors in
  project CSS (`.ds-button .wp-block-button__link`).
- **`name` attribute is dropped, not renamed** in the reference inline rules:
  the visible text is a runtime seam, so a static `aria-label` derived from
  the contract's `name` would desynchronize from injected text.
- **Attrs JSON can never break the comment**: the serializer mirrors
  Gutenberg's `serializeAttributes()` (the `--` sequence and `<`, `>`, `&`,
  `\"` become unicode escapes) — same guarantee in PHP at runtime via
  `AbstractComponent::blockAttrsJson()`.

## Extending the set

Add curated entries in a **project copy** of the mapping (point
`mappings.blockMarkup` at it): new `nodeNameToBlock` rows (incl. custom
`wonderwp/*` blocks) and their `blocks` templates. Derive each template from
the block's actual save output (create one in the editor, inspect the post
content) — never from memory.
