# Axis groups and override format

## The 5 standard axis groups

| Group | Purpose | Selector pattern | Examples |
|---|---|---|---|
| `style` | Visual appearance / color scheme | `.is-style-{value}` | `style:brand`, `style:dark-blue` |
| `size` | Dimensional scaling | `.is-size-{value}` | `size:sm`, `size:md`, `size:xl` |
| `color` | Color theme override | `.is-color-{value}` | `color:dark`, `color:light` |
| `state` | Component state (non-interactive) | `.is-state-{value}` | `state:active`, `state:selected` |
| `interaction` | User interaction | `:hover`, `:active`, etc. | `interaction:hover`, `interaction:disabled` |

If a requested axis group is not one of these 5, ask the user to confirm or map it to
the closest standard group.

## defaultVariant rules

- **Appearance groups** (`style`, `size`, `color`): MUST be declared in `defaultVariant`
  in the base `contract.json`. Adding a new appearance axis means picking its default value.
- **State / interaction groups**: NEVER in `defaultVariant`.

## Override format (DTCG)

Each override entry follows the same DTCG format as base mappings (see
`rules/contract-schema.md`, § Aesthetic):

```json
"<part>.<property>": {
  "$value": "{<token-path>}",
  "$type": "<dtcg-type>",
  "$extensions": {
    "com.wonderwp.cssVar": "--ds-<slug>-<var>"
  }
}
```

Rules:

- **Key format**: `<part>.<property>` dot-notation matching the base composition.
- **`$value`**: DTCG token reference `{path}` or a literal value.
- **`$type`**: MUST match the base mapping's `$type` for the same property.
- **`$extensions.com.wonderwp.cssVar`**: the SAME CSS var as the base — the variant
  overrides the value, not the var name.
- **Gradient values**: object format `{ angle, stops: [{ color, position }] }` with `$type: "gradient"`.
- **Hardcoded values**: literal `$value` + `$description`.
