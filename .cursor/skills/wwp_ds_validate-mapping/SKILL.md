---
name: validate-mapping
description: >-
  Validates contract-mapping JSON against mapping-format.md — $type/$version/walks,
  semantic vs aesthetic section, classNames, templates, defaults, slots;
  corrective-table output. Use when a mapping is created/edited or before running
  load-contract. Mapping-only; never reads contracts or tokens.
metadata:
  domain: wonderwp-design-system
---

> **Path convention:** `{p}` = `plugins/wonderwp-design-system`.

# Validating a contract mapping

## Goal

Confirm one or more `*.json` mapping documents (under
`{p}/scripts/mappings/` or wherever a project keeps its mappings) conform to
`rules/mapping-format.md`. On failure, present a corrective table naming each
finding and the exact fix.

Scoped: validates **mapping documents only**, never a contract or tokens.
Contract checks → `validate-contract`; tokens checks → `validate-tokens`.

## Rules

- **R-PIPE-5 — Validate before publish:** Whenever a mapping is created or
  edited, run this skill before invoking `load-contract`; a failing mapping
  produces undefined output.
- **R-PIPE-8 — Surface, don't silence:** Every finding appears in the table
  verbatim from the validator — no filtering. (Both rules: `rules/pipeline.md`.)
- **Mapping-only scope:** Pass `--mapping <path>[,<path>...]`. The skill never
  reads any contract, tokens, or token file.
- **Engine consistency:** The script reuses the same
  `lib/load/validate-mapping.mjs` module the engine runs at transform time — a
  mapping that passes here also passes the engine's load-time check, and vice
  versa.

## What is checked

Top-level, classNames, and the matching semantic/aesthetic section — full
checklist in [checks](references/checks.md). Canonical definitions:
`rules/mapping-format.md`.

## Workflow

```mermaid
flowchart LR
    A[Step 1: Run validator on mapping path(s)] --> B[Step 2: Render corrective table] --> C[Step 3: Invite fix or confirm pass]
```

### Step 1: Run validator

**Do:**

```bash
node {p}/scripts/validate-mapping.mjs \
  --mapping {p}/scripts/mappings/to-html.json
```

- Several at once, comma-separated:
  `--mapping {p}/scripts/mappings/to-html.json,{p}/scripts/mappings/to-css.json`.
- Capture stdout, stderr, and the exit code. Each mapping reports
  independently; the script exits 1 if any one mapping fails.

**Success criteria:** validator ran; per-mapping findings and exit code captured.

### Step 2: Render corrective table

**Do:** Emit one markdown table grouping every finding under the mapping
file(s): columns `Severity | Mapping | Section | Rule | Finding | What to fix`,
`❌` for errors (the only severity the validator raises — every violation is
structural), one row per finding, each ending with a concrete fix. Format spec,
severity legend, and example rows: [corrective-table](references/corrective-table.md).

**Success criteria:** every captured finding has exactly one actionable row; or,
on exit 0 with zero findings, the table is omitted and `✓ mapping(s) valid —
<paths>` is shown.

### Step 3: Invite fix or confirm pass

**Do:**

- **All pass (exit 0):** confirm with the mapping path(s) validated — safe to
  pass to `load-contract`.
- **At least one finding (exit 1):** after the table, ask:
  > "Fix the listed issues now? (yes — walk me through each / no — leave the
  > mapping as-is, I'll handle later / abort the workflow)"
- **Missing input (exit 2):** surface the missing path and stop.

The skill does NOT auto-fix. It points; the user acts.

## Caller contract

| Caller | When invoked | Mode |
|---|---|---|
| `load-contract` | Optionally, before running the engine on a fresh/recently-edited mapping | bare path |
| standalone | When a mapping is created or edited | user-chosen mapping(s) |

The engine in `load-contract.mjs` already runs the same validator at load time
and refuses any invalid mapping — this skill is a deliberate editor-time check
that lets the user fix issues outside the transform pipeline.

## Resources

| Type | Path | Description |
|---|---|---|
| Script | `{p}/scripts/validate-mapping.mjs` | Mapping validator CLI |
| Engine | `{p}/scripts/lib/load/validate-mapping.mjs` | Underlying validator module (also used by the transform engine) |
| Reference | `rules/mapping-format.md` | Mapping schema (top-level, semantic, aesthetic, classNames, substitution grammar) |
| Reference | [references/checks.md](references/checks.md) | What this skill checks, per section |
| Reference | [references/corrective-table.md](references/corrective-table.md) | Corrective-table format, legend, example |
| Sibling | `skills/validate-contract/SKILL.md` | Per-component contract validation (do not overlap) |
| Sibling | `skills/validate-tokens/SKILL.md` | Tokens + token-families validation (do not overlap) |
| Input | `{p}/scripts/mappings/*.json` | Reference mapping documents shipped with the plugin |
