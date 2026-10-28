# Capture Manifest Format

The `capture-figma-screenshots` skill returns a structured manifest that callers
iterate programmatically to generate PR content.

## JSON shape

```jsonc
{
  "capturedAt": "<ISO-8601>",
  "summary": {
    "figma":      { "captured": 3, "skipped": 1, "errors": 1 },
    "playwright": { "captured": 2, "skipped": 2, "errors": 1 }
  },
  "results": [
    {
      "slug": "badge",
      "layer": "atoms",
      // Figma capture
      "figmaStatus":   "captured",          // "captured" | "skipped" | "error"
      "figmaPath":     "ai_resources/design-system/components/atoms/badge/screenshots/figma-reference.png",
      "figmaUrl":      "https://www.figma.com/design/rZMS2uacVpDQSj2ZkSLcS6/REC---Core-Components?node-id=272-1776",
      // Playwright capture
      "renderedStatus": "captured",         // "captured" | "skipped" | "error"
      "renderedPath":   "ai_resources/design-system/components/atoms/badge/screenshots/rendered.png",
      "renderedSkipReason": null,
      // Conformance
      "conformance": "both",                // "both" | "figma-only" | "rendered-only" | "none"
      // Usage — derived from contract.json for the PR "How to use" section
      "componentName":   "Badge",
      "defaultVariant":  { "color": "transparent" },
      "cascadeOrder":    ["color"],
      "variantKeys":     ["color:transparent", "color:blue", "color:green"],
      "cssContract": {
        "required": ["--ds-badge-root-padding-inline", "..."],
        "optional": ["--ds-badge-root-display", "..."]
      },
      "templateHtml":  "<span class=\"ds-badge\"><span class=\"ds-badge__label\">Placeholder</span></span>"
    },
    {
      "slug": "heading",
      "layer": "atoms",
      "figmaStatus":    "skipped",
      "figmaPath":      null,
      "figmaUrl":       "https://www.figma.com/design/...",
      "figmaSkipReason": "no Figma node-id in source URL",
      "renderedStatus": "skipped",
      "renderedPath":   null,
      "renderedSkipReason": "no preview target",
      "conformance":    "none",
      "componentName":  "Heading",
      "defaultVariant": {},
      "cascadeOrder":   [],
      "variantKeys":    [],
      "cssContract":    { "required": [], "optional": [] },
      "templateHtml":   null
    }
  ]
}
```

## Status values

### `figmaStatus` / `renderedStatus`

| Value | Meaning |
|---|---|
| `captured` | Image fetched and written to disk. `*Path` is set. |
| `skipped` | Excluded before attempting capture. `*SkipReason` explains why. |
| `error` | Capture attempted but failed. `*SkipReason` contains the error message. |

### `conformance`

| Value | Meaning for PR status column |
|---|---|
| `both` | Both images present — side-by-side comparison possible. |
| `figma-only` | Only Figma reference present (Playwright skipped/errored). |
| `rendered-only` | Only rendered image present (Figma skipped/errored). |
| `none` | Neither image captured. |

## PR section 1 — Side-by-side conformance table

`extract-from-figma` generates this table from results where `conformance !== "none"`.
The PNGs are committed to the PR branch and referenced via the raw GitHub base URL
`{RAW} = https://github.com/<owner>/<repo>/raw/<branch>` prefixed onto each result's
repo-root-relative `figmaPath` / `renderedPath`:

```markdown
## Visual conformance

| Component | Figma reference | Rendered (Playwright) | Status |
|---|---|---|---|
| `atoms/badge` | ![Figma]({RAW}/ai_resources/design-system/components/atoms/badge/screenshots/figma-reference.png) | ![Rendered]({RAW}/ai_resources/design-system/components/atoms/badge/screenshots/rendered.png) | ✅ both |
| `atoms/button` | ![Figma]({RAW}/…/atoms/button/screenshots/figma-reference.png) | ![Rendered]({RAW}/…/atoms/button/screenshots/rendered.png) | ✅ both |
| `molecules/card-editorial` | ![Figma]({RAW}/…/molecules/card-editorial/screenshots/figma-reference.png) | _(card not found in preview — rebuild styleguide)_ | ⚠ partial |
| `atoms/heading` | _(no Figma node-id)_ | _(no preview target)_ | ❔ skipped |
```

Status column legend: ✅ both / ⚠ partial (one image only) / ❔ skipped (no images) / ✗ errors.

For results where both images are present (`conformance === "both"`), the
reviewer can visually compare the two columns to spot divergences. No automated
pixel-diff is performed here — that is `audit-render-fidelity`'s responsibility.

## PR section 2 — "How to use" component documentation

`extract-from-figma` generates a usage section from `results` fields
`componentName`, `defaultVariant`, `cascadeOrder`, `variantKeys`, `cssContract`,
and `templateHtml`. Generate one subsection per component.

### Example output (for `atoms/badge`)

```markdown
## How to use the components

### Badge (`atoms/badge`)

The Badge atom displays a short label in a pill shape with optional color variants.

#### Default variant

```html
<span class="ds-badge">
  <span class="ds-badge__label">Placeholder</span>
</span>
```

#### Variants

Apply a variant by adding the BEM modifier class:

| Axis | Value | Class modifier |
|---|---|---|
| `color` | `transparent` _(default)_ | _(base class, no modifier)_ |
| `color` | `blue` | `ds-badge--color-blue` |
| `color` | `green` | `ds-badge--color-green` |

**Example — `color: blue`:**

```html
<span class="ds-badge ds-badge--color-blue">
  <span class="ds-badge__label">Placeholder</span>
</span>
```

#### CSS custom properties

Override design tokens at the component level when embedding in a theme:

| Property | Required | Purpose |
|---|---|---|
| `--ds-badge-root-padding-inline` | required | Horizontal padding |
| `--ds-badge-root-height` | required | Pill height |
| `--ds-badge-root-border-radius` | required | Corner rounding |
| `--ds-badge-root-background-color` | required | Surface fill |
| `--ds-badge-label-font-size` | required | Label text size |
| `--ds-badge-root-display` | optional | Inline flex override |
```

### How to derive variant class modifiers from `variantKeys`

Each entry in `variantKeys` has the form `<axis>:<value>`. The class modifier
is `ds-<slug>--<axis>-<value>` (kebab-case). The `defaultVariant` combination
gets no modifier (base class only).

Example: `variantKeys: ["color:transparent", "color:blue"]`, default `{color: "transparent"}`:
- `color:transparent` → base class only (is the default)
- `color:blue` → `ds-badge--color-blue`

When `cascadeOrder` has more than one axis, a full-combination modifier would be
`ds-<slug>--<axis1>-<val1>--<axis2>-<val2>` — but for the PR table, show each
axis independently (one row per axis/value pair).

## Path convention

All `*Path` values are **relative to the project root** (not `{ds}`), so they
can be passed directly to `git add` to stage the committed PNGs.

For the PR **description**, prefix each `*Path` with the raw GitHub base URL —
GitHub does NOT resolve repo-relative image paths inside a PR body (only inside
committed Markdown files). Define:

    {RAW} = https://github.com/<owner>/<repo>/raw/<branch>

deriving `<owner>/<repo>` from `git remote get-url origin` and `<branch>` from
the pushed PR branch, then reference each image as `{RAW}/<figmaPath>`. The
branch must be pushed before the URLs resolve. If the branch name contains
slashes, use the unambiguous `.../raw/refs/heads/<branch>/<path>` form.
