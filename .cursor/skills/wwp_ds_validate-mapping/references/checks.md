# What validate-mapping checks

Every item is drawn from `rules/mapping-format.md` — this file is a reviewer's
checklist of how the validator applies that spec. Canonical definitions (the
full top-level shape, section schemas, and substitution grammar) live in
`rules/mapping-format.md`; do not duplicate them here.

## Top-level

- `$type === "contract-mapping"`
- `$version` ∈ known versions (`1` in v1)
- `$target` is a non-empty string identifying the output format
- `walks` ∈ `{ "semantic", "aesthetic" }`
- `classNames` block present and well-formed
- Exactly one of `semantic` / `aesthetic` present, matching `walks`

## Class names

- `format === "bem"` (the only supported value in v1)
- `rootPart` is a non-empty string
- `blockTemplate` and `elementTemplate` substitutions limited to their allowed
  placeholders (`{slug}`, `{part}`)

## Semantic section (when `walks: "semantic"`)

- `nodeNameToTag` is an object whose values are non-empty strings
- `attributes.passthrough` / `attributes.drop` are arrays
- `attributes.rename` is a `name→name` object
- `text.escape` ∈ `{ "html", "none", "json-string" }`
- `selfClosing` is an array of tag names
- `slot.strategy === "placeholder"` and `slot.template` only uses `{atom}`,
  `{part}`, `{props}` placeholders
- `defaults.variantsOnRoot` is a boolean; when true,
  `defaults.variantClassTemplate` only uses `{axis}`, `{value}`

## Aesthetic section (when `walks: "aesthetic"`)

- `selectors.blockTemplate` / `selectors.elementTemplate` /
  `selectors.variantModifierTemplate` substitutions match their allowed
  placeholders (`{slug}`, `{part}`, `{selector}`)
- `selectors.rootPart` is a non-empty string
- `tokens.publicVar` / `tokens.privateVar` substitutions match their allowed
  placeholders (`{slug}`, `{cssVarSuffix}`, `{cssVarFull}`)
- `tokens.renderAs` ∈ `{ "two-layer", "direct", "literal" }`
- `tokens.fallbackFromBundle` is a boolean
