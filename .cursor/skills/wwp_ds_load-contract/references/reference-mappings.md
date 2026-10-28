# Reference load mappings

Reference mappings ship under `scripts/mappings/` to validate the engine
end-to-end. All consume the same contract; the engine is target-agnostic. New
output formats are added by shipping a new mapping JSON, not by editing the
engine.

| File | Target | Walks | Demonstrates |
|---|---|---|---|
| `mappings/to-html.json` | `html` | `semantic` | nodeName → tag, attribute filtering, BEM class generation, Slot placeholders, text escape |
| `mappings/to-css.json` | `css` | `aesthetic` | two-layer custom-property pattern, BEM selectors, variant cascade, tokens fallbacks |
| `mappings/to-block-markup.json` | `block-markup` | `block-markup` | nodeName → Gutenberg block, serialized envelope + curated inner templates, nested blocks, inline nodes, seams for code generators (consumed by the `load-wonderwp` provider) |
| `mappings/to-theme-json-literal.json` | `theme-json` | — (source-selected) | tokens.json → `settings` + contract → `styles.blocks`, **resolved** values |
| `mappings/to-theme-json-vars.json` | `theme-json` | — (source-selected) | same, pointing at CSS vars (`settings` → `var(--ds-color-…)`, `styles` → `var(--ds-<slug>-…)`) |

The HTML/CSS mappings produce a **string** (consumed via `load-contract`); the
two `theme-json-*` mappings are **unified** — one file describes the whole
theme.json via `settings` + `styles` sections, and the engine picks the section
from the source type (tokens.json → settings, contract → styles). They produce a
**structured fragment** deep-merged into a shared `theme.json` (consumed via the
dedicated `load-theme-json` skill / `scripts/load-theme-json.mjs`). Only the value
policy (literal / vars) splits into two files. Projects may ship additional
mappings (e.g. `to-tailwind.json`, `to-react-jsx.json`). The formal mapping schema
is `rules/mapping-format.md`.
