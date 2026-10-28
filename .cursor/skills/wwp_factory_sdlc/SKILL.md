---
name: sdlc
description: >-
  End-to-end SDLC spine for WonderWP: issue through pull request. Defines phase order and gates;
  orchestration (which agent and skill per phase) is handled by the wonderwp agent after team discovery.
  Use when the user wants full delivery from an issue to a merge-ready PR with structured orchestration.
metadata:
  domain: wonderwp-agentic-factory
---

# SDLC orchestration (phase spine)

## Goal

Guide a single coordinated run from **issue intake** to **pull request**, using the **wonderwp** orchestrator—without hardcoding which specialist agents or skills implement each phase.

Works the same whether skills are installed for **Cursor** or **Claude Code** (consumer project discovery via `.cursor/agents` or `.claude/agents`).

## Two plans (do not merge)

| Plan | Where | Artefact | Content |
| ---- | ----- | -------- | ------- |
| **Orchestration** | Chat + user validation | `ai_resources/wonderwp/orchestration/<ts>-<slug>.json` | Which agent + skill per SDLC phase; run state |
| **Implementation** | Skill **plan-implementation** only | `ai_resources/plans/<ts>-<slug>.md` | Files, tasks, acceptance criteria |

**Forbidden:**

- File paths or implementation steps in the orchestration chat plan
- A substantive technical plan inline in chat instead of **plan-implementation**
- Ad-hoc coding in phase 5 instead of skill **implement** (`prepare` → `execute` → `finalize`)

## Rules

- Delegate agent/skill choices to **wonderwp** after team map + `./agents.md`.
- **Do not start execution** until the user validates the orchestration plan **and** the run JSON skeleton exists.
- Skipping a phase requires **explicit** user agreement; record in `flags[]` when order deviates (see [references/sdlc-audit-protocol.md](../references/sdlc-audit-protocol.md)).
- Assume the issue is **underspecified** until the **clarity checkpoint** passes (phase 2).
- Update the run JSON after **every** step and phase transition.
- Set `run.status` to `done` only when `retrospective.status === "complete"`.
- SDLC ends at PR; optional **learn** follow-up when the session was harness-heavy (not a 9th phase).

## Phases (fixed order)

1. **Load issue**
2. **Analyse / clarify** — clarity checkpoint mandatory before any orchestration or technical plan
3. **Open branch**
4. **Plan implementation** — skill **plan-implementation** → persisted markdown plan
5. **Implement** — skill **implement** on approved plan
6. **Review**
7. **Document** (domain readme / doc)
8. **Pull request**

## Phase gates

Do not enter phase N+1 until:

- `phases[N].status === "done"` (or `skipped` with user agreement)
- Required steps have `invoked: true` where applicable
- Required artefacts exist:
  - after **branch**: `branch` field set
  - after **plan**: `artifacts.implementation_plan` on disk
  - after **implement**: plan status `implemented` or implement phase done
  - before **pr**: `phases[review].status === "done"`

Record phase `started_at` / `completed_at`; rollup `metrics` per wonderwp protocol.

## Clarity checkpoint (phase 2)

After **read-issue**, **before** any orchestration or technical plan:

1. Score the issue on **what / who / why / how** (see product-manager **clarify** skill checklist).
2. Show the verdict table in chat (pass / gap / contradiction per dimension).
3. **Any gap or contradiction** → invoke **clarify**; no plan until brief validated.
4. **All pass** → write `phases.analyse.clarity_checkpoint` in run JSON with `verdict: "pass"`, brief summary, and `clarify_skipped_reason`.
5. Include a **clarify** step in the default orchestration proposal; remove it only after checkpoint pass is documented.

## Anti-patterns

| Anti-pattern | Why it fails |
| ------------ | ------------- |
| `read-issue` → orchestration or technical plan without checkpoint table | Skips clarify; product fixes land late |
| Technical plan in chat | Bypasses **plan-implementation** and file gate |
| Implementation without **implement** skill | No report, no run JSON sync |
| Marking steps `done` without `invoked: true` | Audit false positives |
| Close-out with empty retrospective | `run.status` must stay `in_progress` |
| `/sdlc` with no run JSON | Orphan run (invisible to audit) |
| Confusing host “plan mode” with SDLC orchestration validation | Wrong gate; wrong artefact |

## Ad-hoc runs

No tracker issue? Still create run JSON with descriptive slug and `flags: ["ad_hoc_run"]`.

## After phase 8

Optional: wonderwp may suggest skill **learn** (patches **ai-rules**) when harness friction thresholds are met—user decides; not part of SDLC delivery.

## Instructions

1. Adopt **wonderwp** orchestrator behaviour end-to-end.
2. Advance phases in order unless user approves a documented permutation.
3. Persist state only in run JSON ([templates/orchestration-run.schema.json](../templates/orchestration-run.schema.json)).
4. On blockers: stop, update JSON (`blocked`), explain, propose options.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Agent | [agents/wonderwp.md](../../agents/wonderwp.md) | Full orchestration protocol |
| Schema | [templates/orchestration-run.schema.json](../templates/orchestration-run.schema.json) | Run JSON shape |
| Example | [templates/orchestration-run.example.json](../templates/orchestration-run.example.json) | Filled sample |
| Audit | [references/sdlc-audit-protocol.md](../references/sdlc-audit-protocol.md) | Compliance checklist |
