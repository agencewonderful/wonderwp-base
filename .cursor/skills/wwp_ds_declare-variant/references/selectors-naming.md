# Selector derivation and naming normalization

## Naming normalization — everything is kebab-case

Axis names, value names, folder names, `defaultVariant` keys, variant keys — all
kebab-case, everywhere. No camelCase, no spaces, no exceptions.

| Input | Normalized axis | Folder path | Selector | defaultVariant key |
|---|---|---|---|---|
| `brand` in axis `style` | `style` | `style/brand/` | `.is-style-brand` | `"style": "brand"` |
| `dark blue` in axis `style` | `style` | `style/dark-blue/` | `.is-style-dark-blue` | `"style": "dark-blue"` |
| `colorScheme` axis, `dark` value | `color-scheme` | `color-scheme/dark/` | `.is-color-scheme-dark` | `"color-scheme": "dark"` |
| `sm` in axis `size` | `size` | `size/sm/` | `.is-size-sm` | `"size": "sm"` |
| `active` in axis `state` | `state` | `state/active/` | `.is-state-active` | _(not in defaultVariant)_ |
| `hover` in axis `interaction` | `interaction` | `interaction/hover/` | `:hover` | _(not in defaultVariant)_ |
| `focus keyboard` in axis `interaction` | `interaction` | `interaction/focus-keyboard/` | `:focus-visible` | _(not in defaultVariant)_ |
| `disabled` in axis `interaction` | `interaction` | `interaction/disabled/` | `[disabled], .is-disabled` | _(not in defaultVariant)_ |
| `pressed` in axis `interaction` | `interaction` | `interaction/pressed/` | `:active` | _(not in defaultVariant)_ |

**Normalization rules:**

- **Axis names**: kebab-case (`colorScheme` → `color-scheme`, `fontSize` → `font-size`).
- **Value names**: kebab-case (`darkBlue` → `dark-blue`, `Extra Large` → `extra-large`).
- **Folder name** = `{kebab-axis}/{kebab-value}/` (`color-scheme/dark/`).
- **defaultVariant keys** = kebab axis names, matching the folder names exactly.
- **Variant key** = `{kebab-axis}:{kebab-value}` (`color-scheme:dark`).
- **axisGroup in `$extensions`** = kebab-case, matching the folder name.

**Enforced end-to-end:** the folder name on disk, the variant key in the composition,
the `defaultVariant` key in the base, and `axisGroup`/`axisValue` in `$extensions` must
all use the same kebab-case string. The validator rejects mismatches.

## Selector derivation

- **Appearance groups** (`style`, `size`, `color`): `.is-{kebab-axis}-{kebab-value}`.
- **Interaction variants**: CSS pseudo-selectors directly, per the table below.
- **Default variant** of an appearance axis: `"com.wonderwp.selector": null` (no CSS
  class — it's the base rendering). The `defaultVariant` in the base `contract.json` is
  the single source of truth for which value is the default; there is no `isDefault`
  flag in the variant file.

### Interaction selector mapping

| Value | Selector |
|---|---|
| `hover` | `:hover` |
| `pressed` | `:active` |
| `focus-keyboard` | `:focus-visible` |
| `focus-within` | `:focus-within` |
| `disabled` | `[disabled], .is-disabled` |
| `loading` | `.is-state-loading` |
