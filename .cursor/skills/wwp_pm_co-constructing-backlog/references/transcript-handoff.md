# Transcript handoff (input artifact)

Structured document produced by **planning-backlog-from-conversation-transcript** and consumed by **co-constructing-backlog** when running a **transcript-informed** session. It is **not** a final [backlog draft](../../building-backlog-plan-from-draft/references/backlog-draft.md); co-constructing still runs the full WonderWp Base → custom domains → blocks → templates loop.

## Required sections

1. **Transcript source** — Full transcript text inline, or a stable file path the session can read, plus optional meeting metadata (date, participants).
2. **Optional executive summary** — Few sentences; does not replace the transcript.
3. **First-pass mining (hypotheses)** — Clearly labeled **non-final**:
   - **WonderWp Base first:** signals that match the [functional-domains.md](functional-domains.md) catalog (search, map, RGPD, multilingual, etc.)—list these hypotheses **before** broader custom-domain epics when possible
   - Goals, decisions, risks, follow-ups inferred from the text
   - Candidate **epic groupings** and **issue titles** for **custom** (CPT / technological) domains (hypotheses to validate in the workshop)
4. **Gaps** — Topics the transcript does not cover; use `NEEDS CLARIFICATION` where inference is unsafe.

## Rules for co-constructing

- **Do not skip** the canonical questions ([Questions to ask](../SKILL.md#questions-to-ask)) because the handoff already lists blocks or domains—use hypotheses as **starting points** and reconcile with the user. Apply **WonderWp Base** reconciliation before custom-domain hypotheses when both appear.
- Follow [functional-domains.md](functional-domains.md), [blocks.md](blocks.md), and [templates.md](templates.md) for each topic; user answers may override transcript hypotheses.
- When promoting hypotheses to a [backlog draft](../../building-backlog-plan-from-draft/references/backlog-draft.md), epic headings use **full uppercase** epic titles — [artifact-language-fr.md](../../../references/artifact-language-fr.md).

## Suggested filename

`.cursor/backlog/<slug>-transcript-handoff.md` (or equivalent).
