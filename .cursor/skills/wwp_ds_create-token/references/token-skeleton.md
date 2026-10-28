# Token write skeleton

The shape inserted at the dot-notation path inside
`{ds}/token-families/<family>.json` (Step 6), and merged into `{ds}/tokens.json`
at the same path (Step 7).

```json
{
  "$value": "<normalized-value>",
  "$type": "<type>",
  "$description": "<description-if-provided>",
  "$extensions": {
    "source.role": ["<inferred-role-or-user-provided>"],
    "source.declaredBy": "<source>",
    "createdBy": "create-token"
  }
}
```

## Confirmation draft (Step 5)

Present the final token as a table before any write:

| Field | Value |
|---|---|
| Path | `<dot-notation-path>` |
| Family | `<family>` |
| `$value` | `<normalized-value>` |
| `$type` | `<type>` |
| Source | `<source>` |
| Description | `<description-if-any>` |
