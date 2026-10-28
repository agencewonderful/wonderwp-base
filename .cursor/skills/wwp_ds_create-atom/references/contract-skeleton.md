# Atom `contract.json` skeleton

> The canonical contract schema (top-level shape, aesthetic + semantic
> sections, WHATWG DOM node shape, jointure rules R-CONTRACT-1..3/7, and
> R-CONTRACT-12 — an atom contains no `Slot`) and a full worked atom example
> live in `rules/contract-schema.md`. This file is only a fill-in skeleton for
> Step 5 — do not treat it as the spec.

Write to `{ds}/components/atoms/<slug>/contract.json`:

```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "$type": "component-contract",
  "$extensions": {
    "com.wonderwp.component": "<AtomName>",
    "com.wonderwp.slug": "<atom-slug>",
    "com.wonderwp.layer": "atom",
    "com.wonderwp.composedAt": "<ISO-8601>",
    "com.wonderwp.defaultVariant": { "size": "md", "style": "brand" },
    "com.wonderwp.cascadeOrder": ["size", "style", "appearance", "color-scheme", "interaction"],
    "com.wonderwp.source": {
      "url": "https://www.figma.com/design/<file>/<name>?node-id=<node>",
      "type": "figma"
    },
    "com.wonderwp.childComponents": [],
    "com.wonderwp.cssContract": {
      "required": ["--ds-<slug>-<var>"],
      "optional": ["--ds-<slug>-<var>"]
    }
    /* note: cssContract declares the public CSS custom-property API the contract guarantees;
       the actual CSS file is produced by a downstream transform, not by this skill. */
  },
  "aesthetic": {
    "base": { "<part-name>": { "<property>": { "$value": "{<token-path>}", "$type": "<dtcg-type>", "$extensions": { "com.wonderwp.cssVar": "--ds-<slug>-<part>-<property>" } } } },
    "variants": { "<axis>:<value>": { "$extensions": { "com.wonderwp.selector": ".is-<axis>-<value>" }, "<part>.<property>": { "$value": "...", "$type": "...", "$extensions": { ... } } } }
  },
  "semantic": {
    "nodeType": 1,
    "nodeName": "<PascalCase>",
    "attributes": [{ "name": "part", "value": "root" }, ...],
    "childNodes": [ ... ]
  }
}
```

Node-shape fill-in for the `semantic` section (see `rules/contract-schema.md`
for the authoritative definition):

```json
{
  "nodeType": 1,
  "nodeName": "<PascalCase>",
  "attributes": [
    { "name": "part", "value": "<part-key>" },
    { "name": "<attr-name>", "value": "<string>" }
  ],
  "childNodes": [ /* Element | Text */ ]
}
```

Text node:

```json
{ "nodeType": 3, "nodeName": "#text", "data": "<placeholder content>" }
```
