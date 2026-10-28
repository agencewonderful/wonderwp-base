# What validate-tokens checks

These checks are enforced by the validator (`validate-dtcg.mjs --tokens-only`)
and define the WonderWP tokens + token-family contract that every downstream
consumer (contracts, transform engine, generate-tokens-css) relies on. They are
not restated in `rules/`.

This skill does NOT walk any `{ds}/components/**/contract.json` — run
`validate-contract` for that.

## Tokens (`{ds}/tokens.json`)

- `$schema` and `$metadata` presence.
- `$metadata.schemaVersion` declared.
- Per-family `$type` allow-list enforced (e.g. `color` family entries must
  declare `$type: "color"`, etc.).
- No unexpected top-level keys outside the known token families.

## Token families (`{ds}/token-families/*.json`)

- Section ownership — each family file owns exactly its family key (the
  `color.json` file must declare only `color.*` paths, etc.; the family's
  `ALLOWED_SIDECARS` keys are accepted).
- `$type` values within the family's allow-list.
- **Two-way sync with `tokens.json`** (`create-token` writes both files —
  divergence must not pass):
  - every family token exists in `tokens.json` with identical `$value` and
    `$type`;
  - no orphan — a token present in `tokens.json` but absent from its family
    file fails (family files are the write-side source of truth);
  - a family file missing while `tokens.json` carries that family section
    fails as out-of-sync.
