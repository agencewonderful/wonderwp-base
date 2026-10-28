---
name: generate-tokens-css
description: >-
  Generates framework-agnostic --ds-* CSS custom properties from DTCG token families
  (composite typography skipped). Use after creating/editing/deleting tokens, after a
  Figma extraction, or to realign token CSS with definitions. Triggers: "generate
  token CSS", "regenerate --ds-* variables".
argument-hint: "--design-system <path>"
allowed-tools:
  - Bash
  - Read
metadata:
  domain: wonderwp-design-system
---

> **Path convention:** `{ds}` = design-system root (default `ai_resources/design-system`).
> Paths relative to project root.

# Generate Token CSS

## Goal

Translate all DTCG token families into deterministic, framework-agnostic CSS
custom property files using the `--ds-*` namespace.

## Rules

- **`--ds-` prefix + kebab-case token path** (camelCase → kebab).
- **Skip composites:** typography composites are classname-mapping dependent and
  handled by the theme layer. Empty families (shadow, animation) are skipped too.
- **Deterministic:** tokens sorted alphabetically within `:root`; same input →
  byte-identical output (date header excluded).

Arguments, output tree, naming examples, and determinism guarantees:
[references/output-and-naming.md](references/output-and-naming.md).

## Workflow

### Step 1: Run the generator

**Do:**

```bash
node plugins/wonderwp-design-system/scripts/generate-tokens-css.mjs \
  --design-system {ds}
```

Optional `--out <dir>` overrides the default `{ds}/dist/tokens`.

**Success criteria:** CSS files written to the output directory.

### Step 2: Verify

**Do:**

1. Verify output in `{ds}/dist/tokens/`.
2. Cross-check that `--ds-*` var names match the refs in component `styles.css`.

**Success criteria:** Token var names align with component CSS references.

## Resources

| Type | Path | Description |
|---|---|---|
| Script | `plugins/wonderwp-design-system/scripts/generate-tokens-css.mjs` | Token CSS generator |
| Input | `{ds}/token-families/*.json` | DTCG token family files |
| Output | `{ds}/dist/tokens/*.css` | Generated CSS files |
| Reference | [references/output-and-naming.md](references/output-and-naming.md) | Arguments, output tree, naming, determinism |
