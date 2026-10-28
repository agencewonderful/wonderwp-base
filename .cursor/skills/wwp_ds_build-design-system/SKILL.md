---
name: build-design-system
description: >-
  One-command build façade: runs the whole Load pipeline (token CSS, per-component
  CSS/HTML, slot composition, preview.html, theme.json) idempotently; restrict with
  --targets. Use to regenerate every derived artifact at once. Triggers: "build the
  design system", "regenerate everything", "one-shot build".
metadata:
  domain: wonderwp-design-system
---

> **Path convention:** `{ds}` = the design-system root (default `ai_resources/design-system`).
> `{p}` = `plugins/wonderwp-design-system`. Paths relative to the project root.

# Building the design system (one command)

## Goal

(Re)generate **all** derived artifacts of a `{ds}` root in one deterministic
pass, hiding the multi-step Load sequence behind a single command. This is the
**façade** over the canonical pipeline — the per-step skills (`generate-tokens-css`,
`load-contract`, `compose-slots`, `load-theme-json`, the `generate-styleguide`
steps) remain available for targeted runs.

Everything stays inside `{ds}` (framework-agnostic — feeds the styleguide).
**Installing** the artifacts into a WordPress theme (theme.json, CSS, PHP
components) is the sibling façade `load-wonderwp` (see
`skills/load-wonderwp/SKILL.md`).

## What it runs

`{p}/scripts/build.mjs` orchestrates, in order, the existing CLIs:

| Stage | Underlying CLI | Output |
|---|---|---|
| token CSS | `generate-tokens-css.mjs` | `{ds}/dist/tokens/*.css` (`--ds-*`) |
| per-component CSS | `load-contract.mjs` + `to-css.json` | `{ds}/components/<layer>/<slug>/styles.css` |
| per-component HTML | `load-contract.mjs` + `to-html.json` | `…/template.html` (semantic:null → skipped) |
| compose slots | `compose-slots.mjs` | molecule `template.html` (slots resolved) |
| style guide | `generate-styleguide.mjs` | `{ds}/preview.html` |
| theme.json | `load-theme-json.mjs` (settings + each component) | `{ds}/dist/theme.json` |

Folder-based variants are resolved automatically (the load CLIs fold them in).

## Usage

```bash
# Everything (default), idempotent
node {p}/scripts/build.mjs --design-system {ds}

# Restrict to specific targets
node {p}/scripts/build.mjs --design-system {ds} --targets styleguide,theme-json

# theme.json referencing the DS CSS vars instead of inlined values
node {p}/scripts/build.mjs --design-system {ds} --targets theme-json --theme-json-variant vars

# Build the style guide and open it
node {p}/scripts/build.mjs --design-system {ds} --targets styleguide --open
```

- `--design-system <path>` — **required**, the `{ds}` root
- `--targets css,html,styleguide,theme-json` — default: all. `styleguide` implies css + html + slots + token CSS.
- `--theme-json-variant literal|vars` — default `literal`. `vars` requires the DS token CSS enqueued at runtime (see `load-theme-json`).
- `--open` — open `preview.html` after a styleguide build.

## Rules

- **Idempotent, not pure** (R-PIPE-4). Every underlying step is deterministic and
  overwrites its output; re-running on unchanged inputs reproduces the same files
  (modulo the date header in token CSS). The build holds no state between runs.
- **No logic duplication.** The façade only sequences existing CLIs — it never
  reimplements token resolution, walks, or merging.
- **Surface, don't silence** (R-PIPE-8). `semantic:null` components are skipped
  (logged), not failed. Per-component failures are collected and listed at the
  end; token CSS failure is fatal (everything references `--ds-*`). Exit code is
  non-zero if any step failed.

## Resources

| Type | Path | Description |
|---|---|---|
| CLI | `{p}/scripts/build.mjs` | The orchestrator |
| Skill | `skills/generate-styleguide/SKILL.md` | The per-step style-guide sequence (now subsumed by `--targets styleguide`) |
| Skill | `skills/load-contract/SKILL.md` | Per-component CSS/HTML Load |
| Skill | `skills/load-theme-json/SKILL.md` | theme.json Load |
| Skill | `skills/generate-tokens-css/SKILL.md` | Token CSS generation |
| Skill | `skills/load-wonderwp/SKILL.md` | Sibling façade — installs the artifacts into a WordPress theme (WonderWP provider) |
