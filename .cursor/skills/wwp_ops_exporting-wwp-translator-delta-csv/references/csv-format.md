# wwp-translator CSV import format

## Delimiter and encoding

- Delimiter: **`;`** (semicolon), not comma.
- Encoding: prefer **UTF-8**. The importer keeps valid UTF-8; it only `utf8_encode`s strings that fail the UTF-8 check.
- Native BO exports are written with `utf8_decode` (Latin-1). Re-importing those works; new agent-built files should stay UTF-8.

## Columns

| Index | Meaning |
| ----- | ------- |
| `0` | msgid (`pot` key) |
| `1..N` | msgstr for language whose **`id` equals the column index** |

Header row is typically:

```text
Clé de traduction;<title id=1>;<title id=2>;...
```

Header **titles are cosmetic** for import: the importer maps body cells by **numeric column index → `LangEntity.id` → locale**, not by header label.

Relevant code: `TranslatorCsvImporterService::import()`:

```php
$j = ($i === 0) ? 'pot' : $langIndex[$i]; // $langIndex[id] = locale
```

## Language id gaps (critical)

If `*_wwp_lang` has non-contiguous ids (e.g. `1..8,10..15` with **no id 9**), a CSV that only lists existing languages in order (like a BO export) will **mis-assign** every locale after the gap on import.

**Always insert an empty column for each missing id** between `1` and `max(id)` inclusive.

Example (Pink Lady local):

| Col | Lang id | Locale |
| --- | ------- | ------ |
| 0 | — | pot |
| 1 | 1 | fr_FR |
| … | … | … |
| 8 | 8 | nb_NO |
| 9 | *(missing)* | *(empty cell)* |
| 10 | 10 | sv_SE |
| … | … | … |
| 15 | 15 | nl_BE |

Rebuild this map from the **target** DB before generating a CSV for preprod/prod.

## One file per textdomain

Import runs on a single plugin/theme edition table. Split keys by domain:

- `wwp-newsletter-delta.csv` → import on `wwp-newsletter`
- `wwp-jeux-delta.csv` → import on `wwp-jeux`

## BO flow after upload

1. Import merges rows into the edition table by matching `pot`.
2. UI shows a notice: imported but **not yet saved**.
3. User must click save for PO/MO (or configured persister) to persist.
