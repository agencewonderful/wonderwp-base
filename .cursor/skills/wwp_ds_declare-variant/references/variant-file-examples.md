# Variant file structure and examples

## File structure

```
{ds}/components/atoms/button/
  contract.json                    ← base only (no variants section)
  README.md
  style/
    brand/contract.json            ← variant DTCG file
    dark-blue/contract.json
    white/contract.json
    blue/contract.json
    transparent-black/contract.json
    transparent-white/contract.json
  size/
    sm/contract.json
    md/contract.json
    xl/contract.json
  interaction/
    hover/contract.json
    pressed/contract.json
    focus-keyboard/contract.json
    disabled/contract.json
```

**Convention:** `{axis-name}/{variant-value}/contract.json` — the folder hierarchy IS
the variant key (`style:brand` = `style/brand/`). No variants are stored inline in the
base `contract.json`.

## Default vs non-default

Every variant declares its FULL overrides, whether default or not — same DTCG structure,
same overrides section. The only difference is the selector:

- **Default variant**: `"com.wonderwp.selector": null` (no CSS class — base rendering).
- **Non-default variant**: `"com.wonderwp.selector": ".is-{axis}-{value}"` (or a pseudo
  for interaction).

The `defaultVariant` in the base `contract.json` is the single source of truth for which
variant is default per axis. There is no `isDefault` flag — each variant file is
self-contained and independently declares all its token overrides.

## Non-default variant — `style/dark-blue/contract.json`

```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "$type": "component-variant",
  "$extensions": {
    "com.wonderwp.component": "Button",
    "com.wonderwp.slug": "button",
    "com.wonderwp.axisGroup": "style",
    "com.wonderwp.axisValue": "dark-blue",
    "com.wonderwp.selector": ".is-style-dark-blue"
  },
  "overrides": {
    "button.background": {
      "$value": "{color.primary.darkBlue.500}",
      "$type": "color",
      "$extensions": { "com.wonderwp.cssVar": "--ds-button-bg" }
    },
    "label.color": {
      "$value": "{color.neutral.50}",
      "$type": "color",
      "$extensions": { "com.wonderwp.cssVar": "--ds-button-color" }
    }
  }
}
```

## Default variant — `style/brand/contract.json`

```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "$type": "component-variant",
  "$extensions": {
    "com.wonderwp.component": "Button",
    "com.wonderwp.slug": "button",
    "com.wonderwp.axisGroup": "style",
    "com.wonderwp.axisValue": "brand",
    "com.wonderwp.selector": null
  },
  "overrides": {
    "button.background": {
      "$value": "{color.primary.red.500}",
      "$type": "color",
      "$extensions": { "com.wonderwp.cssVar": "--ds-button-bg" }
    },
    "label.color": {
      "$value": "{color.neutral.50}",
      "$type": "color",
      "$extensions": { "com.wonderwp.cssVar": "--ds-button-color" }
    }
  }
}
```

## Interaction variant — `interaction/hover/contract.json`

```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "$type": "component-variant",
  "$extensions": {
    "com.wonderwp.component": "Button",
    "com.wonderwp.slug": "button",
    "com.wonderwp.axisGroup": "interaction",
    "com.wonderwp.axisValue": "hover",
    "com.wonderwp.selector": ":hover"
  },
  "overrides": {
    "button.background": {
      "$value": "{color.primary.pink.900}",
      "$type": "color",
      "$extensions": { "com.wonderwp.cssVar": "--ds-button-bg" }
    }
  }
}
```
