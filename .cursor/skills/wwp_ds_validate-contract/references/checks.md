# What validate-contract checks

Every item is drawn from `rules/contract-schema.md` — this file is a
reviewer's checklist of how the validator applies that spec. The canonical
definitions (including the full R-CONTRACT-1..12 text, node interfaces, and
reserved nodeNames) live in `rules/contract-schema.md`; do not duplicate them
here.

## Aesthetic section

- Each mapping has `$value`, `$type`, `$extensions.com.wonderwp.cssVar`.
- Legacy `token`/`cssVar` flagged (errors in `--strict`).
- Every `{token-ref}` resolved against `tokens.json`.
- `defaultVariant` declared when appearance variants exist.
- `cssContract` present in `$extensions`.

## Semantic section

- Per-node WHATWG shape: `nodeType ∈ {1, 3, 11}`.
- Text node: `nodeName === "#text"`, string `data`, no
  `attributes`/`childNodes` (R-CONTRACT-7).
- Element node: PascalCase `nodeName`, `attributes` is an array of
  `{ name, value }`, `childNodes` is an array.
- Reserved `Slot` has `atom` attribute pointing to a registered slug AND
  empty `childNodes`.
- **R-CONTRACT-1** — every non-Slot Element's `part` attribute matches a key
  in `aesthetic.base`.
- **R-CONTRACT-3** — root Element has `part: "root"`.
- **R-CONTRACT-2** — every Slot's `atom` resolves in `components.json` **and**
  has its own `contract.json` on disk. Error in both modes.
- Reverse jointure warning — every `aesthetic.base` key should appear as an
  Element somewhere in `semantic`.

## Composition — no free electrons

Error under `--strict` (a contract a creation skill just wrote), warning
otherwise (an existing contract nobody touched), so the rules can land on a
design system that predates them.

- **R-CONTRACT-9** — a molecule declares no component `nodeName` inline
  (`Label`, `Icon`, `Badge`, `Heading`, …): those are atoms, reached through a
  `Slot`. Container names stay free.
- **R-CONTRACT-10** — a molecule holds no Text node; content travels in the
  Slot's `props`.
- **R-CONTRACT-11** — `childComponents`, the Slot `atom`s, and the registry's
  `dependencies[]` designate the same set, with matching `layer`.
- **R-CONTRACT-12** — an atom contains no `Slot`.

## `semantic: null`

- Warning only — accepted for the migration path; new contracts SHOULD
  populate `semantic`.

## Variants

- Aesthetic overrides only, no semantic at the variant level.
- Token refs resolved, default-variant completeness.
