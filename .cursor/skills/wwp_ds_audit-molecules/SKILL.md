---
name: audit-molecules
description: >-
  Audits the design system's COMPOSITION GRAPH — who composes whom — and reports where
  it has drifted: childless molecules, cycles, slots onto a missing contract, free
  electrons, unused atoms. Organised by molecule, not by rule. Read-only, always exits
  0. Use on "audit molecules", "composition graph", "which molecule composes what",
  "is there a cycle", "molecule that composes nothing", "unused atoms".
metadata:
  domain: wonderwp-design-system
---

> **Path convention:** `{ds}` = project design-system root (default
> `ai_resources/design-system`). `{p}` = `plugins/wonderwp-design-system`.

# Auditing the molecule composition graph

## Goal

Answer one question the per-rule validators cannot: **what does the composition
graph look like, and where has it drifted?** Organised BY MOLECULE, so a reader
sees one component's children at a glance instead of a rule-by-rule listing.

This is deliberately **not** a second conformance report. `audit-conformance`
owns the rules across the whole design system; this skill owns the graph. Where
the two touch — free electrons — this one gives the per-molecule count and
points there for the detail, so they cannot drift into disagreeing.

## What it catches that validation does not

| Finding | Why validation misses it |
|---|---|
| **CHILDLESS** | A molecule with no Slot and no `childComponents` satisfies every rule — both are empty, so they agree — and `--strict` passes it silently. But a component that composes nothing is an atom filed under `molecules/`. |
| **CYCLE** | A slots B slots A. R-CONTRACT-6 requires transform engines to detect this; the validator never has. `compose-slots.mjs` expands Slots recursively, so a cycle is an infinite expansion waiting for the next build. |
| **UNRESOLVED** | A Slot pointing at a component with no contract on disk. |
| **FREE ELECTRONS** | Content or components inlined instead of slotted (R-CONTRACT-9/10) — counted per molecule here, listed with locations by `audit-conformance`. |
| **UNUSED ATOMS** | An atom no molecule slots onto. Not an error — a template may consume it directly — but worth seeing on a board meant to be composed. |

## Workflow

### Step 1: Run the audit

**Do:**

```bash
node {p}/scripts/audit-molecules.mjs --design-system {ds} [--slug a,b] [--json]
```

- `--slug a,b` — restrict the report to these molecules (the graph is still
  built from the whole board, so cycles through excluded molecules are found).
- `--json` — machine-readable output, for a caller that post-processes.

The graph is read from `{ds}/components.json` when present, from disk
otherwise — the script says which.

**Success criteria:** the script exits 0 and prints a report. It **always**
exits 0, even with findings: a drifting graph is a report, not a failure. Only
bad arguments exit 2, so do not read the exit code as a verdict — read the
report.

### Step 2: Read the report

Three sections, in order:

```
COMPOSITION      one line per molecule: slot count · distinct atoms, then its children
DRIFT            CHILDLESS / CYCLE / UNRESOLVED / FREE ELECTRONS, each with its fix
UNUSED ATOMS     atoms no molecule slots onto
→ N/M molecule(s) drifting
```

A `⚠️` in COMPOSITION marks a molecule that appears in DRIFT below.

### Step 3: Route each finding

The skill is **read-only** — it names the correction, never applies it.

| Finding | Route to |
|---|---|
| CHILDLESS | move it to `atoms/` (`create-atom`) or give it children (`create-molecule`) |
| CYCLE | break the loop in the contract — the next build would expand it forever |
| UNRESOLVED | `create-atom` on the missing slug |
| FREE ELECTRONS | `audit-conformance` for the per-finding location, then `create-molecule` rules |
| UNUSED ATOMS | nothing, unless the board is meant to be fully composed |

## Side-effect guarantee

**Read-only.** It loads contracts and the component index and prints a report.
It never writes to `contract.json`, `components.json`, any mapping, or any
generated artefact.

## Caller contract

| Caller | When invoked | Mode |
|---|---|---|
| standalone | After adding or renaming a molecule | full board |
| standalone | Before a build, when a cycle is suspected | full board |
| standalone | Reviewing one component's children | `--slug <slug>` |
| skill | A caller that post-processes the graph | `--json` |

## Resources

| Type | Path | Description |
|---|---|---|
| Script | `{p}/scripts/audit-molecules.mjs` | The audit CLI |
| Input | `{ds}/components.json` | Component index (the graph's declared side) |
| Input | `{ds}/components/<layer>/<slug>/contract.json` | Contracts (the graph's actual side) |
| Reference | `rules/contract-schema.md` | R-CONTRACT-6/9/10/11 — the rules the findings name |
| Sibling | `skills/validate-contract/SKILL.md` | Per-contract structural validation |
| Sibling | `skills/create-molecule/SKILL.md` | Composition rules when fixing a finding |
| Sibling | `skills/create-atom/SKILL.md` | Creating a missing slotted component |
