---
name: implement
description: >-
  Executes an approved plan under ai_resources/plans/: implements each step, checks off
  checkboxes, verifies acceptance criteria, updates the run log, and produces an
  implementation report for the pull request.
  Use when the user asks to implement a plan, execute implementation steps, run the
  approved plan, or continue after plan-implementation handoff.
metadata:
  domain: wonderwp-agentic-factory
argument-hint: prepare | execute | finalize
---

# Implement

## Goal

Execute an **already approved** plan produced by **plan-implementation**, one step at a time, until every acceptance criterion holds—keeping the plan file up to date and producing an **implementation report** ready for pull request creation.

## Rules

- Run in the **consumer project** (not the `ai-rules` repo unless that is the workspace).
- **Required input:** path to `ai_resources/plans/<YYMMDDHHmm>-<feature-slug>.md`, or resolve it from the active handoff / orchestration run JSON (`artifacts.implementation_plan`).
- **Do not replan:** on substantive drift from the plan, stop with `replan needed: <reason>`; never silently rewrite the plan.
- **Live plan updates:** after each validated step, change `- [ ]` to `- [x]` and add a short **Result:** block under the step (what was actually done, any deviation).
- **Delegation:** for each step, follow the **Agent + skill** named in the plan (refresh the team map from `ai_resources/memory/agent-orchestration.md` if stale per wonderwp rules).
- **Run JSON:** after each step, update `ai_resources/wonderwp/orchestration/<same-ts-slug>.json` (phase steps, status, `invoked`, metrics rollup). Do not create legacy `.md` run logs.
- **Commits:** commit only when the user asks or when `./agents.md` explicitly requires it.
- Run actions in order: **`prepare` → `execute` → `finalize`**. Read [references/blocked.md](references/blocked.md) before `execute`.

## Instructions

### Action 1 — `prepare`

**Do:**

1. **Resolve the plan** — read the file at the given path; if missing or unreadable, stop with `plan not found at <path>`; never fabricate a plan.
2. **Verify approval** — the plan must be user-validated (Handoff to implementation filled, or explicit agreement in the current session); otherwise stop and ask for validation.
3. **Branch** — confirm or create the branch listed under Related context; delegate to version-control skills from the team map when needed.
4. **Sync TodoWrite** — align todos `impl-step-1`, `impl-step-2`, … with plan checkboxes; set the first step **in progress**.
5. **Mark the plan** — set **Plan status** to `in-progress` (YAML frontmatter or Plan status section per [plan-template.md](../plan-implementation/references/plan-template.md)).

**Success criteria:** Plan resolved, branch known, todos aligned, status `in-progress`.

### Action 2 — `execute`

Loop each `### Step N` in order:

**Do (per step):**

1. Mark the step **in progress** (TodoWrite; optional note in the plan).
2. **Implement** using the step's Files list and Agent + skill delegation.
3. **Assert** — verify the step's **Acceptance** criterion (tests, lint, inspection); repair and re-assert on failure.
4. **Update the plan:**
   - check the checkbox `- [x]`
   - add **Result:** (1–3 sentences: what was done, deviations from plan, resolved blockers)
5. **Run JSON** — update the matching step and **implement** phase (`done`, `blocked`, or `skipped`) with a short result summary; set `artifacts.implementation_report` path after finalize.
6. **TodoWrite** — mark step **completed**; set the next step **in progress**.

**Guard (stop the loop):**

- **Blocked** — see [references/blocked.md](references/blocked.md): set plan status `blocked`, write a partial implementation report if useful, stop.
- **Drift** — any substantive mismatch with the plan: stop with `replan needed: <reason>`; do not edit the plan beyond the current step's **Result**.

**Success criteria:** All steps checked, or an explicit stop at `blocked` / `replan needed`.

### Action 3 — `finalize`

**Do:**

1. **Global verification** — review every step **Acceptance** criterion; build a pass/fail table for the report.
2. **File inventory** — run `git diff --name-status` (or equivalent) against the feature branch base to list created, modified, and deleted files.
3. **Write the report** — follow [references/implementation-report-template.md](references/implementation-report-template.md); persist to **`ai_resources/wonderwp/implementation/<YYMMDDHHmm>-<feature-slug>.md`** (create the directory if needed). Add a link in the plan's **Implementation report** section and set `artifacts.implementation_report` in the run JSON.
4. **Mark the plan** — set **Plan status** to `implemented`; mark **implement** phase `done` in run JSON.
5. **Hand off to PR** — tell the user the report is ready for **create-pull-request** (SDLC phase 8); point to the report path and PR draft inputs in the report.

**Success criteria:** Report complete (outcome, acceptance table, files changed, deviations, follow-ups, PR draft inputs); plan status `implemented`.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Template | [references/implementation-report-template.md](references/implementation-report-template.md) | PR-ready implementation report shape |
| Reference | [references/blocked.md](references/blocked.md) | When to stop with `blocked` |
| Plan template | [../plan-implementation/references/plan-template.md](../plan-implementation/references/plan-template.md) | Plan status, checkboxes, Result blocks |
| Upstream | [../plan-implementation/SKILL.md](../plan-implementation/SKILL.md) | Produces the approved plan this skill executes |
| Agent | [agents/wonderwp.md](../../agents/wonderwp.md) | Orchestration protocol, run JSON paths |
| Schema | [../../templates/orchestration-run.schema.json](../../templates/orchestration-run.schema.json) | Run JSON shape |
| Downstream | `plugins/wonderwp-version-control/skills/create-pull-request/SKILL.md` | Uses the implementation report for PR body |
