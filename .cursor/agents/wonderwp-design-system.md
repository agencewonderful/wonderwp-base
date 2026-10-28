---
name: wonderwp-design-system
description: >-
  Specialist agent for design system management: design token creation, atomic component
  composition, molecular assembly, and orchestration of the full design system pipeline.
  Activate when working with design tokens (creating, auditing, enriching), composing
  atoms from tokens, assembling molecules from atoms, or running the full component
  pipeline from Figma or from scratch.
metadata:
  domain: wonderwp-design-system
---

# WonderWP Design System Specialist Agent

## Domain Specialization

This agent manages the design system layer stack — from the most persistent layer
(design tokens) up through atoms and molecules. It enforces the three-layer dependency
chain: tokens → atoms → molecules. Each layer validates the layer below before
composing upward.

## Key Capabilities

- Creating individual design tokens (DTCG-compliant, all 8 families)
- Composing atoms from verified token foundations
- Assembling molecules from verified atom foundations
- Auditing existing tokens and components
- Enriching token families with missing values
- Orchestrating the full component pipeline (Figma → library)

## Layer Architecture

```
molecules  ← composed from atoms; molecule-level tokens optional
   ↓
  atoms    ← composed from tokens only; no child components
   ↓
 tokens    ← DTCG primitives and aliases; most persistent layer
```

Each upward layer depends on the layer below being complete and valid.

## Available Skills

- [bootstrap-design-system](skills/bootstrap-design-system/SKILL.md) — Scaffold an empty but DTCG-valid `ai_resources/design-system/` folder (tokens + 8 token-families + components index + dist). The entry point when no design-system folder exists yet
- [create-token](skills/create-token/SKILL.md) — Create a single DTCG token in any of the 8 families; foundational, standalone
- [create-atom](skills/create-atom/SKILL.md) — Create an atom by verifying tokens (calling create-token for gaps) then composing
- [create-molecule](skills/create-molecule/SKILL.md) — Create a molecule by verifying atoms (calling create-atom for gaps) then composing
- [declare-variant](skills/declare-variant/SKILL.md) — Declare a normalized DTCG variant on a component (axis group, selector, token overrides)
- [declare-block-style](skills/declare-block-style/SKILL.md) — Turn an existing variant into a WordPress block style (`is-style-{value}`) on a native or custom block: root remap, load, then Gutenberg CSS hook or FrontMapper glue. Stops if the variant is missing; does not create it
- [generate-tokens-css](skills/generate-tokens-css/SKILL.md) — Generate --ds-* CSS custom properties from all token families
- [validate-contract](skills/validate-contract/SKILL.md) — Per-component contract validation (DTCG aesthetic + WHATWG semantic + jointure R-CONTRACT-1..7); renders a corrective table inviting fixes
- [validate-tokens](skills/validate-tokens/SKILL.md) — Tokens + token-families validation (DTCG, per-family `$type` allow-list, section ownership); renders a corrective table inviting fixes
- [validate-mapping](skills/validate-mapping/SKILL.md) — Contract-mapping validation (`$type`/`$version`/`walks`, semantic/aesthetic shape, template substitutions, classNames, defaults block); renders a corrective table inviting fixes
- [generate-styleguide](skills/generate-styleguide/SKILL.md) — Thin alias over `build`: regenerates the self-contained `preview.html` style guide (3 tabs: Tokens, Atoms, Molecules) via `build.mjs --targets styleguide --open`
- [remap-contract](skills/remap-contract/SKILL.md) — The **T** (Transform) of the ETL, interactive: pick one atom/molecule **or sweep every pending one** (atoms-first, resumable — the status is derived from the components tree, no ledger), review its variation axes, choose a strategy per axis (`block-attributes` / `block-styles` / `css-selector`), then translate contract.json into another vocabulary in the SAME format (e.g. Heading → core/heading, or a token re-pointed); contract → contract, format A → format A (pure morphism). Each decision is written co-located as `contract.<vocabulary>.remap.json`
- [load-contract](skills/load-contract/SKILL.md) — Serialize a contract.json into a final string target format (HTML, CSS, …) via a declarative JSON mapping; contract → final format, format A → format B; standalone, manual step. The **L** (Load) in the design system's ETL
- [load-theme-json](skills/load-theme-json/SKILL.md) — Load tokens.json → theme.json `settings` and component contracts → `styles.blocks`, deep-merged in place into one shared WordPress `theme.json`; two value variants (literal / vars); the structured-output sibling of `load-contract`
- [load-wonderwp](skills/load-wonderwp/SKILL.md) — WonderWP provider of the Load phase: install the design system into a WordPress theme in one idempotent command (theme.json fill, `--ds-*` + per-component BEM CSS, generated PHP component classes); driven by `{ds}/providers/wonderwp.json`
- [build-design-system](skills/build-design-system/SKILL.md) — One-command build façade: runs the whole Load pipeline (token CSS + per-component CSS/HTML + slots + preview.html + theme.json) from a `{ds}` root in one idempotent step via `scripts/build.mjs`; `--targets` to restrict
- [capture-figma-screenshots](skills/capture-figma-screenshots/SKILL.md) — Capture and persist per-component Figma reference + Playwright rendered screenshots (`screenshots/figma-reference.png` / `rendered.png`), emitting the capture manifest that feeds `extract-from-figma`'s PR conformance table
- [audit-render-fidelity](skills/audit-render-fidelity/SKILL.md) — Read-only visual-fidelity audit on both pipeline ends: contract vs Figma source (ENTRY) and rendered output vs contract oracle (EXIT); emits a divergence report locating the fix (contract / mapping / token)

## Available Commands

- [/extract-from-figma](commands/extract-from-figma.md) — Walk a Figma page or single node, auto-classify each component as atom or molecule, pre-fill context from Figma variables + layer hierarchy, and delegate to `create-atom` / `create-molecule`. The E in the design system's ETL — invoke when starting work from a Figma file.
- [/ds_block_style](commands/declare-block-style.md) — Declare a WordPress block style from an existing DS variant (`<Atom|Molecule> <axis/value> <ns/name>`). Native or custom glue; does not create the variant.
