---
name: co-constructing-backlog
description: >-
  Facilitates an interactive session to co-build a backlog with the user: guided
  questions, branching decisions, and repeated turns until the user ends the loop.
  Persists the backlog draft to ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md on init and after every turn.
  Supports live workshops or transcript-informed sessions (after planning-backlog-from-conversation-transcript).
  Use when workshopping backlog items or when continuing from a transcript handoff.
metadata:
  domain: wonderwp-product-manager
---

# Co-constructing a backlog

## Goal

Run a **collaborative backlog session** with the user—questions, decisions, and optional updates to a backlog artifact—**until the user stops the loop**, converging on a **backlog draft** (epics and issue titles only) per [building-backlog-plan-from-draft/references/backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md).

## Entry modes

| Mode | When | How to start |
| ---- | ---- | ------------ |
| **A — Live workshop** | No transcript | Open the session; use the questions below from scratch. |
| **B — Transcript-informed** | A [transcript handoff](references/transcript-handoff.md) exists from **planning-backlog-from-conversation-transcript** | Load the handoff (path or paste); treat the transcript as **evidence**; use hypotheses as starting points only. |

## Rules

- **Language (artefacts):** Backlog draft, notes enregistrées et prompts d’atelier **en français** — [references/artifact-language-fr.md](../../references/artifact-language-fr.md). **Titres d’epics** : **majuscules intégrales** (`### Epic: NOM DE L’EPIC`) ; normaliser si l’utilisateur dicte autrement.
- **Loop control:** After each turn (question batch answered, decision applied, or artifact updated), **continue** the session unless the user clearly ends it. Treat as stop signals: phrases like **stop**, **done**, **exit**, **that’s enough**, **end session**, or an explicit request to pause/stop. If unclear, ask once whether to continue or end.
- **No silent invention:** Do not fill in concrete requirements the user has not agreed to; use **`NEEDS CLARIFICATION`** where detail is still open (aligned with planning-backlog-from-conversation-transcript).
- **Transcript-informed (mode B):** Do **not** skip [Questions to ask](#questions-to-ask) or the `references/` subprocedures because the handoff already lists domains or blocks—reconcile hypotheses with the user and update or discard them explicitly. Prefer **WonderWp Base** hypotheses from the handoff **before** custom-domain hypotheses when updating the draft.
- **Categories:** Per-issue categories are **not** assigned in this draft stage; they are collected in **building-backlog-plan-from-draft**. When `./agents.md` defines allowed categories, ensure the list exists for downstream use (ask, persist to `agents.md`, or defer to enrichment).
- **Output target:** Converge on a single **backlog draft** matching [backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md). Next: **building-backlog-plan-from-draft** → **creating-epics-and-issues-from-backlog-plan**. If the user only wants notes or a partial draft, keep the artifact clearly labeled and offer to expand toward the draft shape.
- **Reference subprocedures:** For the canonical backlog questions below, follow the matching file under [`references/`](references/) (read it when you start that topic, not necessarily in full at session start).
- **Canonical draft file (mandatory):** At **project root**, persist the backlog draft to `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md`, where `<yymmdd>` is **YYMMDD** (e.g. `250326` for 2025-03-26). Create the directory `ai_resources/backlog-drafts/` if it does not exist.
- **Initialize and create the file immediately:** On entering this skill, **without waiting** for the first Q&A turn: resolve today’s path; **if** that file **does not exist** or is empty, **seed** from [references/default-backlog-skeleton.md](references/default-backlog-skeleton.md) (replace `# Backlog — <projet>` with a concrete H1) and **write the file to disk**. **If** the file **already exists** and is non-empty (resume same day), **load** it as the working draft—do **not** re-seed on top unless the user asks to reset.
- **Persist after every turn:** After each user answer (or each loop iteration that changes understanding), **write the full current backlog draft** to the same file. The draft is built **progressively** so interruption of the chat does not lose work already written.
- **Default skeleton:** When seeding a **new** empty draft, **seed** from [references/default-backlog-skeleton.md](references/default-backlog-skeleton.md): copy the markdown block, replace `# Backlog — <projet>` with a concrete H1 (project name or slug). Do **not** overwrite a non-empty file without user consent.
- **Merge:** Add epics and issues from **WonderWp Base**, then **custom** functional domains, then blocks, and templates—**after** the default skeleton unless the user asks to insert elsewhere. **Deduplicate** epics by title (case-insensitive, trimmed); if a workshop epic matches a default epic title, merge new issue bullets into that section instead of duplicating the heading.
- **Do not remove** default epics or default issue lines unless the user **explicitly** asks to drop or replace them (if they want a minimal backlog, confirm before trimming).

## Instructions

1. **Read** `./agents.md` when you need tracker context, allowed categories, or tool hints.
2. **If mode B:** Load [transcript-handoff.md](references/transcript-handoff.md)-compliant content; restate purpose (workshop grounded in transcript).
3. **Initialize backlog draft and file:** Create `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` per [Canonical draft file](#rules) and [Default skeleton](#rules): seed if new/empty, else load existing; **write to disk** before the first question.
4. **Open** the session with a short purpose statement; state the **resolved file path** so the user can open it; walk through [Questions to ask](#questions-to-ask) in order unless the user asks to reorder or skip.
5. For each question, **open and follow** the linked subprocedure in `references/` before synthesizing backlog items.
6. **Apply** [Decision branches](#decision-branches) after relevant answers; record outcomes in the working backlog or scratch notes.
7. **Update** the backlog draft (epics and issue **titles**, `NEEDS CLARIFICATION` where needed), then **write the full file** to `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` **after every turn** that changes the draft.
8. **Check** for a stop signal after each turn. If none, move to the **next** question (and its subprocedure) or a refinement pass.
9. **Close** with a concise summary of agreed items, open clarifications, the **path** to the draft file, and suggested next step: **building-backlog-plan-from-draft**, then **creating-epics-and-issues-from-backlog-plan**.

## Questions to ask

Ask **one main question at a time** (or a tight cluster if the user prefers). **WonderWp Base comes first** so catalog-backed needs are captured before custom CPT/technological domains—see [functional-domains.md](references/functional-domains.md) (*Analysis order*). Default order:

| Order | Question | Subprocedure |
| ----- | -------- | ------------ |
| 1 | Which **WonderWp Base** needs apply (catalog plugins)? | [references/functional-domains.md](references/functional-domains.md) — phase 1 |
| 2 | Beyond that, what **custom functional domains** (CPT-driven, custom technological)? | [references/functional-domains.md](references/functional-domains.md) — phase 2 |
| 3 | Does this project need specific **custom blocks**? If yes, which ones? | [references/blocks.md](references/blocks.md) |
| 4 | What **templates** are needed on this project? | [references/templates.md](references/templates.md) |

Optional follow-ups (after the four above or when relevant): scope/timeframe, stakeholders, constraints, risks, rough priority—use the same rigor as in planning-backlog-from-conversation-transcript handoff mining.

## Decision branches

- **If** the user wants a **minimal viable** backlog only → cover the four canonical questions lightly; favor `### Unscoped` or a single epic; defer optional follow-ups.
- **If** the user wants **full tracker readiness** → complete WonderWp Base + custom domains / blocks / templates capture per `references/`, then finalize a **backlog draft** (issue titles under epics); categories and bodies come from **building-backlog-plan-from-draft**.
- **If** the user **cannot decide** priority → capture `NEEDS CLARIFICATION` and offer a default ordering hypothesis for confirmation.
- **If** templates depend on blocks → sequence issue titles so **shared blocks** land before **template integration** when the user agrees.

Suggested flow across the main questions:

```mermaid
flowchart LR
    WW[WonderWp Base] --> FD[Custom functional domains]
    FD --> CB[Custom blocks]
    CB --> TP[Templates]
    TP --> BL[Update backlog draft]
    BL --> Stop{User stops?}
```

## Workflow

### Step 1: Session start

**Do:**

1. State that you are running the **co-constructing backlog** flow; if mode B, confirm the transcript handoff is loaded.
2. Skim `agents.md` for categories and tracker context if not already loaded.
3. **Create or resume the draft file:** Ensure `ai_resources/backlog-drafts/` exists. Use `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` (`yymmdd` = YYMMDD for today). If the file is missing or empty, seed from [references/default-backlog-skeleton.md](references/default-backlog-skeleton.md) and **write it**; if it already has content, **read** it as the working draft. **Do not** delay file creation until after the first answer.
4. Tell the user the **absolute or workspace-relative path** to the draft file.
5. Ask the first question group from [Questions to ask](#questions-to-ask) (or the most relevant one).

**Success criteria:** User understands the mode; the draft file exists on disk with initial or resumed content; the user has started answering.

### Step 2: Question and decision cycle

**Do:**

1. Capture answers; apply [Decision branches](#decision-branches).
2. Reflect back a short synthesis before adding or changing backlog items.
3. Maintain or update a **backlog draft** per [backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md).
4. **Write the complete current draft** to `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` after each update (progressive save).

**Success criteria:** Each turn ends with updated understanding, an updated draft, and the file on disk matching the draft.

### Step 3: Stop check

**Do:**

1. If the user sent a **stop signal**, go to Step 4.
2. Otherwise, continue with the next question group or refinement.

**Success criteria:** Loop only exits when the user ends it or explicitly asks to finalize.

### Step 4: Session end

**Do:**

1. Summarize epics/issue titles and remaining `NEEDS CLARIFICATION`.
2. Confirm the backlog draft path: `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` (final write should already match the last turn).
3. Point to **building-backlog-plan-from-draft**, then **creating-epics-and-issues-from-backlog-plan**, when the user is ready to enrich and create tracker items.

**Success criteria:** Clear handoff and no forced continuation.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Reference | [references/transcript-handoff.md](references/transcript-handoff.md) | Input from transcript prep (mode B); consumed by this skill |
| Subprocedure | [references/functional-domains.md](references/functional-domains.md) | WonderWp Base first, then custom domains; catalog and mapping |
| Subprocedure | [references/blocks.md](references/blocks.md) | How to list and ticket custom blocks |
| Subprocedure | [references/templates.md](references/templates.md) | How to inventory templates and cross-link blocks/domains |
| Reference | [references/default-backlog-skeleton.md](references/default-backlog-skeleton.md) | Epics et titres d’issues par défaut pour amorcer le premier brouillon |
| Convention | `ai_resources/backlog-drafts/<yymmdd>-backlog-draft.md` (project root) | Fichier brouillon persisté à l’init et après chaque tour (`yymmdd` = YYMMDD) |
| Reference | [building-backlog-plan-from-draft/references/backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md) | Canonical backlog draft shape (output of this skill) |
| Related skill | [planning-backlog-from-conversation-transcript](../planning-backlog-from-conversation-transcript/SKILL.md) | Optional pre-step: transcript → handoff for mode B |
| Related skill | [building-backlog-plan-from-draft](../building-backlog-plan-from-draft/SKILL.md) | Enrich draft → enriched plan |
| Related skill | [creating-epics-and-issues-from-backlog-plan](../creating-epics-and-issues-from-backlog-plan/SKILL.md) | Create epics and issues from enriched plan |
