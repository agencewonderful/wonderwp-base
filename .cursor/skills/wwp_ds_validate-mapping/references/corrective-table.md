# Corrective table — mapping validation

The output of Step 2. One markdown table, every finding grouped under its
mapping file. Read the validator's output and map each finding into a row.

## Columns

| Column | Content |
|---|---|
| Severity | `❌` for errors |
| Mapping | The mapping file the finding belongs to (e.g. `to-html.json`) |
| Section | `top-level` / `semantic.*` / `aesthetic.*` / `classNames` |
| Rule | The check name (`$type`, `walks`, `unknown placeholder`, `enum`, …) |
| Finding | What the validator reported |
| What to fix | Concrete corrective action — name the field and the fix |

## Severity legend

- `❌` — error: the only severity the mapping validator raises today. Every
  violation is structural and blocks the transform; there are no warnings.

## Table rules

- One row per finding — never collapse two.
- The "What to fix" column is **actionable**: name the file, the field, and the
  corrective action. When the fix is documented in `rules/mapping-format.md`,
  reference the relevant section.
- If the validator exits 0 with zero findings, omit the table and confirm
  `✓ mapping(s) valid — <paths>`.

## Example

| Severity | Mapping | Section | Rule | Finding | What to fix |
|---|---|---|---|---|---|
| ❌ | `to-html.json` | top-level | `$type` | `$type` is `"contract-map"` (must be `"contract-mapping"`) | Set `$type` to `"contract-mapping"` exactly |
| ❌ | `to-html.json` | top-level | `walks` | `walks` is `"both"` (must be `"semantic"` or `"aesthetic"`) | Pick one walk axis — markup targets use `"semantic"`, stylesheet targets use `"aesthetic"`. Split into two mappings if both are needed |
| ❌ | `to-html.json` | `semantic.slot.template` | unknown placeholder | `{slug}` is not a legal substitution here | Replace with `{atom}` (or `{part}` / `{props}`); see `rules/mapping-format.md` § Substitution grammar |
| ❌ | `to-css.json` | `aesthetic.tokens.renderAs` | enum | `"three-layer"` is not in `{ "two-layer", "direct", "literal" }` | Change to one of the supported values; `"two-layer"` is the WonderWP default |
