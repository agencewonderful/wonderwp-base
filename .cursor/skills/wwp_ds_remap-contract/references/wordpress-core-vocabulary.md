# Vocabulary cheatsheet — WonderWP → WordPress core blocks

A starter reference: which WonderWP semantic `nodeName` maps to which WordPress
core block, with the attributes the target expects. Use it to pre-fill proposals;
anything **not** listed is `NEEDS DECISION`, never a guess.

> This is a hand cheatsheet. The discoverability of blocks can later be replaced
> by a **pre-built index** (a script that registers each core block, its
> attributes and a reference output) used as a cache / source of truth — the
> cheatsheet and the index share the same shape: *source type → target block +
> attributes*.

## Identity map

| Source `nodeName` | Target block | Required / added attributes | Notes |
|---|---|---|---|
| `Heading` | `core/heading` | `level` (default `2`) | `level` is the H1–H6 axis — see open question (semantic vs variant). |
| `Label` | `core/paragraph` | — | Eyebrow / caption text. Often `constant: { className: "is-style-eyebrow" }`. |
| `Paragraph` / `Text` | `core/paragraph` | — | |
| `Button` | `core/button` | — | `keep` `aria-label`; usually `drop` `type` (core/button manages its own). Label text stays as the child text node. |
| `Icon` | `core/html` | — | No native icon block; carry as raw HTML, or map to an icon plugin block if the project has one (`NEEDS DECISION`). |
| `Image` | `core/image` | — | `keep` `alt`, `src`. |
| `Picture` | `core/group` | — | Wrapper around `core/image`; or `core/image` directly if no `<picture>` semantics needed. |
| `Section` | `core/group` | `tagName` (e.g. `article`, `section`) via `constant` | The outer container of a molecule. |
| `Layout` / `Group` | `core/group` | optional `className` | Generic structural wrapper. |
| `List` | `core/list` | — | |
| `ListItem` | `core/list-item` | — | |
| `Separator` | `core/separator` | — | |
| `Link` | `core/button` or inline | — | `NEEDS DECISION`: a standalone link block vs inline markup. |

## Attribute conventions (common)

- **Preserve plumbing:** `part`, `atom`, `props` carry over automatically.
- **`className`** is the usual home for WonderWP BEM/variant intent on a core block.
- **`tagName`** sets the HTML element a `core/group` renders (`div` by default).
- **Booleans** that are `false` by default → `omitWhen: { <attr>: "false" }`.

## Slots

A molecule's `Slot` keeps its `atom` slug; the child atom is transformed by its
own `<child>.remap.json`. The parent stays a tree of `core/group` + `Slot`s — the
composition seam is preserved (no inlining).

## Not covered yet → `NEEDS DECISION`

Form controls (`Input`, `Select`, `Textarea`), media (`Video`, `Embed`),
navigation, and any project-specific or plugin block. Add a row here (or to the
index) once the user confirms the target — that confirmation becomes a reusable
convention.
