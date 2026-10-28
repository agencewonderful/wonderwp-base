# Corrective table — contract validation

The output of Step 2. One markdown table, every finding grouped under its
target component. Read the validator's section-grouped output and map each
finding into a row — never parse the format yourself.

## Columns

| Column | Content |
|---|---|
| Severity | `❌` for errors, `⚠️` for warnings |
| Component | `<layer>/<slug>` the finding belongs to |
| Section | `aesthetic` / `semantic` / variant |
| Rule | The rule ID or check name (`DTCG`, `R-CONTRACT-1`, `tokens ref`, `reverse jointure`, …) |
| Finding | What the validator reported |
| What to fix | Concrete corrective action — name the file, field, and fix |

## Severity legend

- `❌` — error: contract is not usable until fixed. In `--strict` mode, legacy
  `token`/`cssVar` formats are errors.
- `⚠️` — warning: accepted but should be corrected (unresolved tokens refs,
  reverse-jointure gaps, `semantic: null`).

## Table rules

- One row per finding — never collapse two.
- The "What to fix" column is **actionable**: name the file, the field, and
  the corrective action. If the fix is naturally delegated to another skill
  (`create-token`, `declare-variant`, …), name the skill explicitly.
- If the validator exits 0 with zero findings, omit the table and confirm
  `✓ contract(s) valid — <targets>`.

## Example

| Severity | Component | Section | Rule | Finding | What to fix |
|---|---|---|---|---|---|
| ❌ | atoms/button | aesthetic | DTCG | `root.background` uses legacy `token` field | Replace `token` with `$value`, add `$type` and `$extensions.com.wonderwp.cssVar` |
| ❌ | atoms/button | semantic | R-CONTRACT-1 | Element `Label` references part `lable` (typo) not declared in aesthetic.base | Fix the `part` attribute to match a key in `aesthetic.base`, or add `aesthetic.base.lable: {}` if it is a pure wrapper |
| ⚠️ | atoms/button | aesthetic | tokens ref | `{color.primary.blue.500}` unresolved against tokens.json | Run `create-token` to add the missing token, or correct the reference path |
| ⚠️ | atoms/button | semantic | reverse jointure | `aesthetic.base.icon` has no Element in semantic | Add an `Icon` Element with `part: "icon"` to the semantic tree, or remove `icon` from aesthetic.base |
