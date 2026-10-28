---
name: plan-implementation
description: >-
  Drafts a technical implementation plan from issues, goals, and repository context: ordered tasks,
  files to change, dependencies, risks, and acceptance checks, saved as markdown under ai_resources/plans/.
  Use when the user asks to "plan implementation", "technical plan", "plan before coding", "break work into
  steps", validate a build approach after analysis, or when WonderWP or SDLC orchestration needs a persisted,
  user-approved plan and handoff to implementers.
metadata:
  domain: wonderwp-agentic-factory
---

# Plan implementation

## Goal

Produce an **implementation plan** that domain specialists can execute, **structured** per [references/plan-template.md](references/plan-template.md), and **persist** it in the consumer project under `ai_resources/plans/` with a filename aligned to the current orchestration run. Close with a **handoff** so the next step is unambiguous.

## Rules

- Run in the **consumer project** (not the `ai-rules` repo unless that is the workspace).
- Align the filename with **wonderwp** orchestration: **`ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md`** using the **same** `YYMMDDHHmm` (local, zero-padded) and **feature-slug** (kebab-case) as the active run JSON in `ai_resources/wonderwp/orchestration/` when this plan is part of that run. If there is no run yet, pick a new timestamp and slug consistent with the issue or task title.
- After persisting the plan, update the run JSON: `artifacts.implementation_plan`, mark **plan** phase progress, and rollup metrics per wonderwp protocol.
- **Do not** publish a substantive technical plan only in chat—the persisted markdown file is the implementation plan artefact.
- Ensure **`ai_resources/plans/`** exists before writing.
- Map tasks to **agent + skill** using the team map from `ai_resources/memory/agent-orchestration.md` (refresh if stale per wonderwp rules).
- **Every implementation step** in the saved plan must be a **single todo**: one `- [ ]` line (or equivalent) per step in the markdown file, plus a matching entry in the **TodoWrite** tool (see Instructions).
- The plan is **not** final until the user has **validated** it (questions resolved + explicit agreement). So keep asking questions and refining the plan until the user agrees with it.

## Instructions

### Step 1: Gather inputs

**Do:**

1. Use the issue analysis / user goal and any loaded issue details.
2. Inspect relevant areas of the repo (paths, existing patterns, tests) as needed for a realistic plan.

**Clarify with AskQuestion:** If scope, constraints, or tradeoffs are ambiguous, use the **AskQuestion** tool (when available) with concrete options—for example: scope variant, testing depth, breaking-change tolerance, or which of two file layouts to follow. If AskQuestion is unavailable, ask the same questions conversationally.

**TodoWrite (initial):** Create a short todo list for **this planning session** only (e.g. gather context → draft plan → user validation → persist → handoff). Use `merge: true` when updating status as you go.

**Success criteria:** Enough context to list atomic work items without guessing file locations blindly.

### Step 2: Draft the plan (use the template)

**Do:**

1. Follow [references/plan-template.md](references/plan-template.md) **exactly** for top-level sections: **Title**, **Summary of intent**, **Related context**, **Proposed implementation flow** (mermaid), **Implementation steps** (each step = one todo + files + agent/skill + acceptance), **Dependencies and ordering**, **Risks and unknowns**, **Handoff to implementation**.
2. List **atomic tasks** in order; show **dependencies** in the mermaid diagram and/or the Dependencies section.
3. For each implementation step, list **relevant file paths** (create vs modify when non-obvious).
4. Keep the mermaid chart readable: prefer a small number of nodes; use subgraphs only when they clarify parallel work.

**Success criteria:** The plan matches the template, is actionable, and is ready for user review.

### Step 3: Validate with the user

**Do:**

1. Present the draft (or key choices) and use **AskQuestion** when a **binary or multiple-choice** decision remains (when the tool is available).
2. Incorporate answers; adjust steps, files, and diagram as needed.
3. Obtain **explicit** user agreement to proceed to implementation (same gate as SDLC: no coding on this skill alone until agreed).

**Success criteria:** User approves the plan content.

### Step 4: Persist and mirror todos

**Do:**

1. Write the full plan to **`ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md`** using the template shape; include at the top references to issue(s), branch if known, and orchestration run JSON path if one exists.
2. Update the matching run JSON (`artifacts.implementation_plan`, plan phase steps) before handoff.
3. **TodoWrite:** Create one todo item per **implementation step** in the plan (ids stable and ordered, e.g. `impl-step-1`, `impl-step-2`). Descriptions should match the plan’s step titles. Set the first step **in progress** when implementation is about to start; otherwise leave implementation todos **pending** until handoff. Use `merge: true` when updating.

**Success criteria:** File exists; naming convention matches; markdown checkboxes and TodoWrite list are aligned.

### Step 5: Hand off to implementation

**Do:**

1. Fill **Handoff to implementation** in the saved file per the template (plan path, first step, suggested implementer, branch, out of scope). Set **Suggested implementer** to wonderwp using **implement** unless a domain specialist owns Step 1 directly.
2. In the chat, state clearly: the plan is approved; **next action** is to run the **implement** skill on the plan file (`prepare` → `execute` → `finalize`); point to the plan path and the first todo.
3. Treat this as a **handoff**: the consumer workflow should continue with [../implement/SKILL.md](../implement/SKILL.md) using the persisted plan and TodoWrite items—not re-planning from scratch.

**Success criteria:** User knows where the plan lives, what the first implementation todo is, and that follow-up work is **implement**, not another planning pass unless they explicitly reopen scope.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Template | [references/plan-template.md](references/plan-template.md) | Required shape: title, intent, mermaid, steps as todos with files, handoff |
| Downstream | [../implement/SKILL.md](../implement/SKILL.md) | Executes the approved plan and produces the implementation report |
| Agent | [agents/wonderwp.md](../../agents/wonderwp.md) | Orchestration protocol, run JSON paths |
| Schema | [../../templates/orchestration-run.schema.json](../../templates/orchestration-run.schema.json) | Run JSON shape |
