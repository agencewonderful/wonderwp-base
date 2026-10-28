# Routes ↔ outputs

With default `paths.*` (see `references/provider-config.md`):

| Route (`--routes`) | Config gate | Underlying CLI | Output |
|---|---|---|---|
| `tokens-css` | `routes.tokensCss` | `generate-tokens-css.mjs` | `{theme}/design-system/tokens/<family>.css` (+ `_index.css`) |
| `theme-json` | `routes.themeJson` | `load-theme-json.mjs` (once for `tokens.json`, once per component, same target) | `{theme}/theme.json` — merged in place, existing content preserved |
| `components-css` | `routes.componentsCss` | `load-contract.mjs` + `to-css.json` | `{theme}/design-system/<layer>/<slug>/<slug>.css` (base + variant modifiers in one file) |
| `php` | `routes.phpComponents` | `generate-php-components.mjs` | see below |

Combined layout — **one self-contained folder per component** (PHP + CSS +
future JS together):

```
{theme}/design-system/
├── Component.php                      # interface: getMarkup() — pure HTML
├── Hydratable.php / Variantable.php   # data-injection / variant contracts
├── Hydration.php / Markupable.php / Variations.php   # capability traits
│                                      # (no base class — each component composes
│                                      #  exactly the capabilities it needs)
├── tokens/<family>.css + _index.css   # --ds-* custom properties
├── atoms/<slug>/
│   ├── <ClassName>.php                # generated — one per atom
│   └── <slug>.css                     # base + variant modifiers
└── molecules/<slug>/
    ├── <ClassName>.php
    └── <slug>.css
```

Failure policy (mirrors `build.mjs`): invalid config/mapping → exit 2 before
any write; token CSS failure → fatal (everything references `--ds-*`);
per-component failures → collected, reported, exit 1; `semantic: null`
components → skipped + logged on markup routes (their CSS and theme.json
styles still load).

Everything is **regenerated on every run** (idempotent overwrite) — treat the
outputs as build artifacts: never hand-edit, re-run the façade instead.
