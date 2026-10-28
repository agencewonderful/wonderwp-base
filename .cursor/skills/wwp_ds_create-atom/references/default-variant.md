# Default variant combination

After all variant axes are declared (each via the `declare-variant` skill),
**define the default combination (mandatory)** when variants exist. For each
appearance axis (not interaction/state), the user must pick exactly one value
as the default:

> "Which combination of variants should be the **default** rendering?"

| Axis | Default value |
|---|---|
| size | `md` |
| style | `brand` |

Store as `defaultVariant: { "size": "md", "style": "brand" }` (written to
`$extensions.com.wonderwp.defaultVariant`).

Rules:

- **Interaction/state axes** are excluded from the default.
- Every appearance axis must have exactly one default value.
- The default combination must reference existing declared variants.
