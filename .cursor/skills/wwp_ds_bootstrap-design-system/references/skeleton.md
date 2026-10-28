# Bootstrap skeleton

What `scripts/bootstrap-design-system.mjs` writes under `{ds}/`:

```
{ds}/
  tokens.json                   ← DTCG tokens, $schema + $metadata, 8 empty families
  token-families/
    color.json                  ← { color: {} } with $schema + $metadata.ownsFamily
    typography.json
    spacing.json
    sizing.json
    border.json
    shadow.json
    animation.json
    opacity.json
  components.json               ← { atoms: [], molecules: [] }
  components/
    atoms/                      ← empty directory
    molecules/                  ← empty directory
  dist/
    tokens/                     ← empty; will hold --ds-* CSS once generate-tokens-css runs
```

The script writes ONLY this empty skeleton. It does NOT write any
`template.html`, `styles.css`, `README.md`, or anything under
`components/atoms/` or `components/molecules/`. Those arrive when the user
calls `create-atom` / `create-molecule`. Token content is never invented
(R-PIPE-2). See `rules/contract-schema.md` for the schema each future
contract will follow and `rules/pipeline.md` for the R-PIPE-* rules.
