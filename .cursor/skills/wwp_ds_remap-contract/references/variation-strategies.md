# Variation-axis mapping strategies

When a contract is remapped toward a block vocabulary (e.g. `wordpress-core`),
every **variation axis** of the component (its `{axis}/{value}/contract.json`
folders — see `declare-variant`) must be given a **mapping strategy**: how does
that axis express itself on the target block?

Two block strategies exist today. Both are specializations of a single base
strategy — **remap the variant's CSS selector to another selector** — which is
the original purpose of contract remapping. They differ only in *which* selector
the target induces and *who* owns it (the block's attribute system vs the
block-styles registry).

## Base strategy — `css-selector`

Re-point the variant's `com.wonderwp.selector` to another CSS selector
(e.g. `.is-style-dark-blue` → `.wp-block-x.is-dark`). Nothing else changes: the
variant stays a pure aesthetic override, only its activation selector moves to
the target vocabulary.

Use **alone** when neither block mechanism fits — typically the `state` and
`interaction` axis groups, whose selectors are CSS pseudos (`:hover`,
`:disabled`, `[aria-expanded="true"]`) and carry over as-is or with a minimal
rewrite.

## `block-attributes`

The axis becomes an **attribute of the target block**. Each axis value maps to
an attribute value the block already exposes; the selector becomes the one that
attribute induces.

Example — a `Heading` with an `h1…h6` level axis mapped to `core/heading`:
the axis becomes the `level` attribute; the induced selectors are the element
selectors `h1…h6`. Another — an alignment axis on `core/paragraph` becomes
`align`, inducing `.has-text-align-center`.

Choose this when the axis changes the **markup or a native capability** of the
block (level, alignment, tagName, fontSize, color supports). The semantic side
is encoded with the existing attribute rules (`values` / `defaults` / `rename`)
of the remap mapping.

## `block-styles`

The axis becomes a **registered block style**: each value maps to an
`is-style-{value}` class, the WordPress block-styles picker owns the switch,
and the induced selector is `.is-style-{value}`.

Choose this for **appearance** axis groups (`style`, `size`, `color`) that are
pure CSS — no markup change — and should surface in the editor's Styles panel.
On the semantic side this usually pairs with a `constant` / `values` rule on
`className`.

Recording this strategy in the remap is not enough in v1 (`R-REMAP-8`). To
register the picker, align CSS to `.is-style-{value}`, and glue a custom
block, follow [`declare-block-style`](../declare-block-style/SKILL.md).

## Default strategy per axis group

| Axis group | Default proposal | Rationale |
|---|---|---|
| `style` | `block-styles` | Pure appearance; editor Styles panel |
| `size` | `block-styles`, or `block-attributes` if the block exposes it (e.g. `fontSize`) | Depends on target block supports |
| `color` | `block-attributes` (color supports), else `block-styles` | Core color supports induce `.has-*-color` classes |
| `state` | `css-selector` | Data/ARIA-driven; no block mechanism |
| `interaction` | `css-selector` | CSS pseudos carry over |

Defaults are **proposals** — each axis is confirmed by the user in the strategy
review (R-ASK-3), never silently applied.

## Semantic strategy — native block identity

Orthogonal to the variation axes: the component's **HTML identity** is remapped
to a **native WordPress block** (`Heading` → `core/heading`, via the mapping's
`semantic.nodeNames`). This is what lets the **Load** phase later produce a
`content` output — serialized block markup destined for `post_content` — with
the semantic adaptation done there, instead of generating PHP + native HTML
templates. The block-markup Load target already exists
(`scripts/mappings/to-block-markup.json`); the semantic remap is its
prerequisite.

## The `variations` record (documentary in v1)

The per-axis strategy decisions are recorded in the remap mapping under a
top-level `variations` key (formal spec: `rules/remap-variations.md`):

```jsonc
"variations": {
  "level": { "strategy": "block-attributes", "attribute": "level" },
  "style": { "strategy": "block-styles",
             "selectors": { "dark-blue": ".is-style-dark-blue" } },
  "interaction": { "strategy": "css-selector",
                   "selectors": { "hover": ":hover" } }
}
```

**Engine v1 does not execute this section** — it neither rewrites the variant
folder files nor their selectors. The record is the durable decision (source of
truth on disk, `R-PIPE-6`); execution is reserved for a future `$version`.

Because nothing executes it, the carnet is held to `R-REMAP-8` instead:

- its **shape is validated** (unknown `strategy`, `block-attributes` with no
  `attribute`, mistyped top-level key → hard error) — the only moment a
  never-read section can be checked;
- it is **cross-checked against the variant folders** on every run (declared
  axis or value that no longer exists; axis on disk with no strategy) —
  warnings, so an undecided or drifted carnet never blocks a legitimate remap;
- the engine prints a per-axis **`RECORDED, NOT EXECUTED`** notice spelling out
  what each strategy has not done.

State this limit to the user *while they confirm the strategies*, not after —
a decision that reads as applied sends the next reader debugging the wrong
layer (`R-PIPE-8`).
