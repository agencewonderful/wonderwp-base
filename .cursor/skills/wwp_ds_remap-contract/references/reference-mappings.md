# Remap mapping format — examples

Remap mappings (`*.remap.json`) are **project data**, not plugin assets: they
encode one design system's per-component vocabulary decisions (specific slugs and
token paths). They live in the **consuming project** under
`ai_resources/design-system/components/<layer>/<slug>/contract.<vocabulary>.remap.json`
(co-located with the contract it overrides),
authored/persisted by [`remap-contract`](../SKILL.md) itself.
The plugin ships the **engine** (`scripts/lib/remap/`) and the **format spec**
(`rules/remap-format.md`) — never the per-component mappings.

A remap declares one or both axes via `remaps: [...]`. Below are minimal format
examples for each axis (copy the shape, not the values).

## Semantic axis — identity + attributes

Rewrites a component's identity (`nodeName` → target) and attributes
(`rename` / `keep` / `drop` / `defaults` / `constant` / `omitWhen`). Example —
a `Heading` mapped to the WordPress `core/heading` block with a default level,
and a `Label` mapped to `core/paragraph` carrying an eyebrow style class:

```jsonc
{
  "$type": "contract-remap", "$version": 1,
  "remaps": ["semantic"],
  "from": { "component": "Heading", "vocabulary": "wonderwp" },
  "to":   { "component": "core/heading", "vocabulary": "wordpress-core" },
  "semantic": {
    "nodeNames":  { "Heading": "core/heading" },
    "attributes": {
      "Heading": { "defaults": { "level": "2" } },
      "Label":   { "constant": { "className": "is-style-eyebrow" } }
    }
  }
}
```

## Aesthetic axis — token references + css-var names

Re-points token references and css-var names toward another token vocabulary,
keeping the DTCG envelope (`$value`/`$type`) intact:

```jsonc
{
  "$type": "contract-remap", "$version": 1,
  "remaps": ["aesthetic"],
  "from": { "component": "Heading", "vocabulary": "wonderwp" },
  "to":   { "component": "Heading", "vocabulary": "wordpress-theme" },
  "tokens": {
    "refRename":    { "color.text.neutral.50": "color.foreground" },
    "refPrefix":    { "typography": "font" },
    "cssVarPrefix": { "--ds-heading-": "--wp-heading-" }
  }
}
```

The full schema (every rule, exhaustiveness requirements, `R-REMAP-*`) is in
`rules/remap-format.md`. New target vocabularies are added by authoring new
mappings in the project — never by editing the engine.
