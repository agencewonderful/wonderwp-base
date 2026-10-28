---
name: exporting-wwp-translator-delta-csv
description: >-
  Builds semicolon-delimited CSV files for wwp-translator BO import from
  feature translation deltas (PO/MO keys added or changed for a domain).
  Use when language files are not deployed (BO is source of truth), after a
  preprod/prod deploy, or when the user asks to re-administer / import
  translation keys created in a feature via wwp-translator CSV import.
metadata:
  domain: wonderwp-ops
---

# Exporting wwp-translator Delta CSV

## Goal

Produce one **import-ready CSV per textdomain** containing only the translation keys introduced or changed by a feature, so they can be imported in the **wwp-translator** admin on preprod/prod (where PO/MO from Git are not the source of truth).

## Rules

- **Never assume deployed PO/MO match Git.** On WonderWP Pink Lady–style projects, language files under `web/app/languages` are administrable in BO and often excluded from deploy; Git is a working copy, not production truth.
- Emit **one CSV per textdomain** (import is done on that plugin/theme’s edit screen).
- CSV format must match `TranslatorCsvImporterService`: delimiter `;`, UTF-8, first column = msgid (`pot`), **column index = language `id`** (see [references/csv-format.md](./references/csv-format.md)).
- **Pad empty columns for missing language ids** (gaps in `*_wwp_lang.id`). Without padding, locales after a gap are mapped to the wrong language.
- Always rebuild the language column map from the **target environment** DB (`SELECT id, locale, title FROM {prefix}wwp_lang ORDER BY id`), not from a hardcoded Pink Lady list.
- Prefer msgstr from local/feature PO files under `web/app/languages/plugins/` (or themes); leave cells empty rather than inventing copy when a locale has no string.
- Do not commit generated CSVs unless the user asks; default output under `web/app/uploads/wwp-translator/` (uploads are typically gitignored).
- After creating/updating this skill in `ai-rules`, run `./.wonderful/scripts/install.sh cursor` from the consumer project to propagate it.

## Workflow

```mermaid
flowchart LR
    A[Detect delta keys] --> B[Load lang id map] --> C[Read PO msgstr] --> D[Write CSV per domain] --> E[Import in BO + save]
```

### Step 1: Identify domains and delta keys

**Do:**

1. Confirm which textdomains are in scope (e.g. `wwp-newsletter`, `wwp-jeux`).
2. Collect msgid keys from one of:
   - User-provided list
   - `git diff develop...HEAD -- web/app/languages/plugins/<domain>-*.po` (new `msgid` blocks)
   - Feature conversation / PR notes
3. Exclude empty msgid / PO headers.

**Success criteria:** A map `{ textdomain: [msgid, ...] }` with only keys that must be re-administered online.

### Step 2: Load language id → locale map from target DB

**Do:**

1. Query the environment where the CSV will be imported (local is OK only if ids match preprod/prod):

```bash
ddev mysql -N -e "SELECT id, locale, title FROM pl_wwp_lang ORDER BY id;"
# adapt table prefix (pl_ / wp_ / …) to the project
```

2. Build columns `0 = pot`, then `1..max(id)` with an **empty placeholder** for any missing id.

**Success criteria:** Column count is `1 + max(id)`, and index `i` maps to locale of language id `i` (or empty if that id does not exist).

### Step 3: Generate CSVs

**Do:**

1. Run the helper (from the consumer project root):

```bash
python3 .cursor/skills/wwp_ops_exporting-wwp-translator-delta-csv/assets/export-delta-csv.py \
  --lang-table pl_wwp_lang \
  --domain wwp-newsletter \
  --key tracking_consent.label.trad \
  --key tracking_consent.brand_default.trad \
  --out-dir web/app/uploads/wwp-translator
```

2. Or pass keys via `--keys-file` / detect from git:

```bash
python3 .cursor/skills/wwp_ops_exporting-wwp-translator-delta-csv/assets/export-delta-csv.py \
  --lang-table pl_wwp_lang \
  --domain wwp-jeux \
  --git-diff develop \
  --out-dir web/app/uploads/wwp-translator
```

3. Spot-check FR (+ one other locale) in the CSV before handing it to the user.

**Success criteria:** Files `web/app/uploads/wwp-translator/<domain>-delta.csv` exist, delimiter `;`, padded columns, non-empty FR (or expected source locale) msgstr for each key.

### Step 4: Import in wwp-translator BO

**Do:**

1. Preprod/prod → **Traductions** → open the plugin/theme for that textdomain → **Importer**.
2. Upload the matching CSV → verify rows in the edition table.
3. **Save** translations (import alone only stages them in the UI).
4. Smoke-test front: keys no longer render as raw msgids.

**Success criteria:** Keys show translated strings on the target environment after save.

## Advanced Features

- **CSV column / id mapping details**: [references/csv-format.md](./references/csv-format.md)
- **Helper script**: [assets/export-delta-csv.py](./assets/export-delta-csv.py)

## Resources

| Type      | Path                         | Description                                      |
| --------- | ---------------------------- | ------------------------------------------------ |
| Script    | `assets/export-delta-csv.py` | Build padded `;` CSVs from PO + DB lang map      |
| Reference | `references/csv-format.md`   | Importer quirks, headers, id gaps, BO steps      |
| Code      | `wwp-translator` `TranslatorCsvImporterService` | Source of truth for parse rules |
