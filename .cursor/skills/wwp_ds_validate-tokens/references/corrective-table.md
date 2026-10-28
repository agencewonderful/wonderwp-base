# Corrective table — tokens validation

The output of Step 2. One markdown table, every finding grouped under
`tokens.json` or the relevant token-family file. Read the validator's output
and map each finding into a row.

## Columns

| Column | Content |
|---|---|
| Severity | `❌` for errors, `⚠️` for warnings |
| File | `tokens.json` or `token-families/<family>.json` |
| Section | `top-level` / the token path / `$metadata` |
| Rule | The check name (`DTCG`, `$type allow-list`, `schemaVersion`, `section ownership`, …) |
| Finding | What the validator reported |
| What to fix | Concrete corrective action — name the file, field, and fix |

## Severity legend

- `❌` — error: the tokens file is not usable until fixed. In `--strict` mode, legacy
  formats are upgraded from warnings to errors.
- `⚠️` — warning: accepted but should be corrected (missing `schemaVersion`,
  section-ownership drift).

## Table rules

- One row per finding — never collapse two.
- The "What to fix" column is **actionable**: name the file, the field, and the
  corrective action. If the fix is naturally delegated to another skill
  (`create-token`, `validate-contract`, `generate-tokens-css`, …), name the
  skill explicitly.
- If the validator exits 0 with zero findings, omit the table and confirm
  `✓ tokens valid ({ds}/tokens.json + {ds}/token-families/*)`.

## Example

| Severity | File | Section | Rule | Finding | What to fix |
|---|---|---|---|---|---|
| ❌ | `tokens.json` | top-level | DTCG | `$schema` missing | Add `"$schema": "https://design-tokens.github.io/community-group/format/"` at the tokens root |
| ❌ | `token-families/color.json` | `color.primary.blue.500` | $type allow-list | `$type: "string"` not allowed in `color` family | Change `$type` to `"color"` (only `color` is allowed for the `color` family) |
| ⚠️ | `tokens.json` | `$metadata` | schemaVersion | `$metadata.schemaVersion` missing | Add `"schemaVersion": 1` under `$metadata` so downstream consumers can detect breaking-change boundaries |
| ⚠️ | `token-families/spacing.json` | `color.primary.blue.500` | section ownership | Token path `color.*` declared in `spacing.json` | Move the entry to `color.json`; each family file owns exactly its family namespace |
