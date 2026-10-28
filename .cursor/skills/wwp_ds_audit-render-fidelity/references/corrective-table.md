# Divergence report — audit-render-fidelity

The output of Step 4. One markdown table combining ENTRY and EXIT findings for
the audited component. Map each finding from the script output into one row —
never collapse two findings into one row.

## Columns

| Column | Content |
|---|---|
| Severity | `❌` for errors, `⚠️` for warnings |
| Phase | `ENTRY` or `EXIT` |
| Component | `<layer>/<slug>` |
| Part.Property | `<part>.<property>` path (e.g. `root.backgroundColor`, `label.fontSize`) |
| Contract value | The token ref or resolved value recorded in the contract (the oracle) |
| Actual / Source value | What the source shows (ENTRY) or what `getComputedStyle` returned (EXIT) |
| Rule / Check | The R-FIDELITY rule or EXIT check name |
| Where to fix | `contract` / `mapping` / `token` — with the exact file and field |

## Severity legend

- `❌` error — definite divergence: the pipeline is materially wrong. Must be
  corrected before the component is considered usable.
- `⚠️` warning — possible divergence or manual review required: the script
  detected a signal but cannot confirm automatically (e.g. an estimation
  candidate, a near-snap, a missing-source note).

## Where-to-fix values

| Value | Meaning | Typical action |
|---|---|---|
| `contract` | The value was mis-transcribed from the source | Re-read the measured value off the design source, update `aesthetic.base.<part>.<property>.$value` in `contract.json` |
| `mapping` | The CSS property target in `to-css.json` is wrong | Edit `{p}/scripts/mappings/to-css.json` — the property key does not map to the right CSS declaration |
| `token` | The token resolves to the wrong value | Run `create-token` to add a new token for the exact measured value, or update the existing token in `{ds}/token-families/<family>.json` |

## Table rules

- One row per finding — never collapse two.
- "Where to fix" is **actionable**: name the file, the field, and what to do.
  When the fix is naturally delegated to another skill (`create-token`,
  `declare-variant`, `validate-mapping`), name the skill explicitly.
- If the script exits with zero findings on both phases, omit the table and
  confirm: `✓ fidelity audit passed — <layer>/<slug>`.

## Example

| Severity | Phase | Component | Part.Property | Contract value | Actual / Source value | Rule / Check | Where to fix |
|---|---|---|---|---|---|---|---|
| ❌ | EXIT | atoms/button | root.backgroundColor | `{color.primary.blue.500}` → `#1A48C0` | `rgb(27, 73, 194)` | Computed style mismatch | `token` — `color.primary.blue.500` resolves to `#1A48C0` but measured as `#1B49C2`; run `create-token` for the exact value or update the token |
| ⚠️ | ENTRY | atoms/button | label.fontSize | `{typography.size.md}` → `16px` | possibly `13px` (estimation candidate) | R-FIDELITY-1 | `contract` — re-measure font-size off the source; if `13px`, create a new token or choose `bind-nearest` explicitly |
| ❌ | ENTRY | atoms/button | root.paddingLeft | `{spacing.md}` → `16px` | silent snap: `{spacing.sm}` → `12px` closer | R-FIDELITY-3 | `contract` — `spacing.md` was not the exact measured token; re-measure padding-left or create a token for the measured value |
| ⚠️ | EXIT | atoms/button | label.lineHeight | `{typography.lineHeight.normal}` → `1.5` | `normal` | Computed style mismatch | `mapping` — `line-height` may not be applying via the CSS contract; check `to-css.json` mapping for `label.lineHeight` |
| ⚠️ | ENTRY | atoms/button | — | — | — | R-FIDELITY-4 | Manual review — confirm every state visible in the source has a declared variant (hover/pressed/focus/disabled) |
