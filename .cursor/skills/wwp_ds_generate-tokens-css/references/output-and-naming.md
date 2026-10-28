# Token CSS — arguments, output, naming, determinism

> `{ds}` = design-system root (default `ai_resources/design-system`).

## Arguments

| Flag | Required | Default | Description |
|---|---|---|---|
| `--design-system <path>` | **yes** | — | Path to the `{ds}/` directory |
| `--out <dir>` | no | `<{ds}>/dist/tokens` | Output directory |

## Output

```
{ds}/dist/tokens/
  _index.css         ← @import aggregator
  color.css          ← --ds-color-*
  typography.css     ← --ds-typography-family-*, --ds-typography-size-*, etc.
  spacing.css        ← --ds-spacing-*
  sizing.css         ← --ds-sizing-*
  border.css         ← --ds-border-*
  opacity.css        ← --ds-opacity-*
```

- Empty families (shadow, animation) are skipped.
- Composite tokens (typography composites) are skipped — they are
  classname-mapping dependent and handled by the theme layer.

## Naming convention

**Rule:** `--ds-` prefix + token path in kebab-case (camelCase → kebab).

| Token path | CSS custom property |
|---|---|
| `color.darkBlue.500` | `--ds-color-dark-blue-500` |
| `typography.family.text` | `--ds-typography-family-text` |
| `typography.size.3xl` | `--ds-typography-size-3xl` |
| `spacing.md` | `--ds-spacing-md` |
| `sizing.buttonHeight.md` | `--ds-sizing-button-height-md` |
| `border.radius.full` | `--ds-border-radius-full` |
| `opacity.50` | `--ds-opacity-50` |

## Determinism guarantees

- All tokens sorted alphabetically by CSS var name within `:root`.
- Same input → byte-identical output (date header excluded).
