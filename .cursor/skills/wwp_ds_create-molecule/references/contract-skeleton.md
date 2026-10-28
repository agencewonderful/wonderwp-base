# Molecule `contract.json` skeleton

> The canonical contract schema (top-level shape, aesthetic + semantic
> sections, WHATWG DOM node shape, the reserved `Slot` Element, jointure rules
> R-CONTRACT-1..3/7, composition rules R-CONTRACT-9..12) and a full worked
> molecule example live in `rules/contract-schema.md`. This file is only a
> fill-in skeleton for Step 6 — do not treat it as the spec.

`childComponents` and the `Slot` nodes are two views of one set: one entry per
distinct Slot `atom`, no more, no less (R-CONTRACT-11). Derive it from the
tree rather than typing it — the load engine reads `childComponents` to scope
molecule-level overrides onto a child, so a slug present in the tree but
missing here loses its override with no error anywhere.

Write to `{ds}/components/molecules/<slug>/contract.json`:

```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "$type": "component-contract",
  "$extensions": {
    "com.wonderwp.component": "<MoleculeName>",
    "com.wonderwp.slug": "<molecule-slug>",
    "com.wonderwp.layer": "molecule",
    "com.wonderwp.composedAt": "<ISO-8601>",
    "com.wonderwp.defaultVariant": {},
    "com.wonderwp.source": {
      "url": "https://www.figma.com/design/<file>/<name>?node-id=<node>",
      "type": "figma"
    },
    "com.wonderwp.childComponents": [
      { "slug": "<atom-slug>", "layer": "atom" }
    ],
    "com.wonderwp.cssContract": { "required": [], "optional": [] }
  },
  "aesthetic": {
    "base": { "<molecule-part-name>": { "<property>": { "$value": "{<token-path>}", "$type": "<dtcg-type>", "$extensions": { "com.wonderwp.cssVar": "--ds-<slug>-<part>-<property>" } } } },
    "variants": { "<axis>:<value>": { "$extensions": { "com.wonderwp.selector": ".is-<axis>-<value>" }, "<part>.<property>": { "$value": "...", "$type": "...", "$extensions": { ... } } } }
  },
  "semantic": {
    "nodeType": 1,
    "nodeName": "<PascalCase>",
    "attributes": [{ "name": "part", "value": "root" }],
    "childNodes": [
      {
        "nodeType": 1,
        "nodeName": "Slot",
        "attributes": [
          { "name": "part", "value": "<slot-part>" },
          { "name": "atom", "value": "<atom-slug>" }
        ],
        "childNodes": []
      }
    ]
  }
}
```

Non-Slot Element shape (see `rules/contract-schema.md` for the authoritative
definition):

```json
{
  "nodeType": 1,
  "nodeName": "<PascalCase>",
  "attributes": [
    { "name": "part", "value": "<part-key>" },
    { "name": "<attr-name>", "value": "<string>" }
  ],
  "childNodes": [ /* Element | Text | Slot */ ]
}
```

`Slot` Element for a child atom (empty `childNodes`; the child atom's own
semantic tree is resolved at transform time):

```json
{
  "nodeType": 1,
  "nodeName": "Slot",
  "attributes": [
    { "name": "part", "value": "<slot-part-name>" },
    { "name": "atom", "value": "<child-atom-slug>" }
  ],
  "childNodes": []
}
```

Optionally include a `props` attribute (JSON-encoded string) to pass props
down to the child atom — e.g. `{ "name": "props", "value": "{\"size\":\"md\"}" }`.
