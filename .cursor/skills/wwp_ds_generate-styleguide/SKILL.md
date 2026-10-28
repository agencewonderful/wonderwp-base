---
name: generate-styleguide
description: >-
  Generates the self-contained preview.html style guide (token CSS + component
  CSS/HTML + slots + 3-tab preview) — thin alias over build.mjs --targets styleguide.
  Use on "generate styleguide", "build the style guide", "regenerate preview.html",
  "open the design-system preview".
metadata:
  domain: wonderwp-design-system
---

> **Path convention:** `{ds}` = design-system root (default `ai_resources/design-system`).
> `{p}` = `plugins/wonderwp-design-system`. Paths relative to the project root.

# Generating the style guide

## Goal

Produce a fully refreshed, self-contained style guide at `{ds}/preview.html`,
deriving every artefact deterministically from the contracts. This is now a
**thin alias** over the [`build-design-system`](../build-design-system/SKILL.md)
façade — the style guide is one of `build`'s targets, so there is a single
implementation (`scripts/build.mjs`) rather than a separately-sequenced pipeline.

## Run it

```bash
node {p}/scripts/build.mjs --design-system {ds} --targets styleguide --open
```

This runs, in order, token CSS → per-component `styles.css` → per-component
`template.html` → molecule slot composition → `preview.html`, then opens it.
Drop `--open` to just (re)generate the file. `semantic:null` components are
skipped (logged); per-component failures are collected and reported; a non-zero
exit means at least one step failed.

The style guide is a **framework-agnostic, human visual check** of the design
system itself (tokens, atoms, molecules), rendered from the design system's own
CSS/HTML. It does **not** need — and must not pull in — `theme.json` or any other
CMS/framework target: those are separate, project-specific Load steps
(`load-theme-json`, …) that come later in the Load phase, never part of the preview.

**Breakpoints.** A component declaring `aesthetic.responsive` gets one axis group
per breakpoint that changes something, each demo rendered in an iframe as wide as
the query allows — the browser evaluates the real media query there, and the group
label carries the resolved threshold. A breakpoint that emits nothing (no query, a
part declared nowhere else, an override restating base) shows the reason instead of
a demo. See `{p}/rules/contract-schema.md` § Responsive overrides.

**Web fonts.** To render the design system's real typefaces in the preview (for an
accurate visual check against the source), declare them in `tokens.json`
`$metadata.webfonts` — an array of stylesheet hrefs (e.g. a Google Fonts URL) or
`{ href, rel?, crossorigin? }` objects. The preview injects them into `<head>` on
**every** regeneration (idempotent — no manual patching of the generated file).
A non-CDN display face just needs a local `@font-face` stylesheet href there.
This is a styleguide concern (the DS's own fonts), not a framework target.

## Rules

The guarantees (R-PIPE-2 no-invention, R-PIPE-4 idempotence, R-PIPE-8
surface-don't-silence, R-PIPE-9 no-attribution) are enforced by `build.mjs` and
the underlying Load CLIs — see [`build-design-system`](../build-design-system/SKILL.md).
Never invoked as a sub-step of `create-token` / `create-atom` / `create-molecule`.

## Resources

| Type | Path | Description |
|---|---|---|
| Skill | [`build-design-system`](../build-design-system/SKILL.md) | The façade this skill delegates to |
| CLI | `{p}/scripts/build.mjs` | Orchestrator (`--targets styleguide`) |
| Output | `{ds}/preview.html` | Self-contained 3-tab style guide |
| Output | `{ds}/dist/tokens/*.css`, `{ds}/components/*/{styles.css,template.html}` | Intermediates the preview assembles |
