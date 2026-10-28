---
name: planning-backlog-from-conversation-transcript
description: >-
  Ingests a conversation or meeting transcript and produces a structured handoff
  for co-constructing-backlog (hypotheses, gaps, transcript reference). Use before
  the workshop when starting from a transcript; full backlog depth comes from
  co-constructing-backlog, not this step alone.
metadata:
  domain: wonderwp-product-manager
---

# Planning backlog from a conversation transcript

## Goal

Turn a **transcript** (pasted text or file path) into a **transcript handoff** document per [co-constructing-backlog/references/transcript-handoff.md](../co-constructing-backlog/references/transcript-handoff.md). The **mandatory** next step for a thorough backlog is **co-constructing-backlog** (WonderWp Base → custom functional domains → blocks → templates). After co-constructing yields a [backlog draft](../building-backlog-plan-from-draft/references/backlog-draft.md), continue with **building-backlog-plan-from-draft** then **creating-epics-and-issues-from-backlog-plan**.

## Rules

- **Language (artefacts):** Rédiger le handoff en **français** (résumé, hypothèses, lacunes) — voir [references/artifact-language-fr.md](../../references/artifact-language-fr.md). Le transcript source peut rester dans sa langue. Pour les **titres d’epics** proposés dans les hypothèses, utiliser les **majuscules intégrales** afin d’aligner le handoff sur le brouillon de backlog.
- **Do not invent** requirements missing from the transcript; record **gaps** and `NEEDS CLARIFICATION` in the handoff.
- **Hypotheses only:** candidate epics and issue titles in the handoff are **non-final** until validated in **co-constructing-backlog**.
- **Categories:** read `./agents.md` for downstream use; this skill does not assign per-issue categories.
- **Output shape:** follow [transcript-handoff.md](../co-constructing-backlog/references/transcript-handoff.md)—not [backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md) (that artifact is produced **after** co-constructing).

## Workflow

### Step 1: Obtain transcript

**Do:**

1. Accept pasted transcript content **or** read the file path the user provides.
2. If the source is unreadable or empty, stop and ask for a valid input.

**Success criteria:** Full transcript text is available (or path is resolvable).

### Step 2: Align `agents.md`

**Do:**

1. Read `./agents.md` and look for **allowed ticket categories** under the GitHub / issue-tracker section.
2. If absent, ask the user for the canonical list (or link) and **update `agents.md`**, or note that co-constructing / enrichment will collect categories later.

**Success criteria:** Category list is in `agents.md` or explicitly deferred.

### Step 3: Mine and write the handoff

**Do:**

1. **WonderWp Base scan (first):** Read the transcript for needs that match the **WonderWp Base** table in [functional-domains.md](../co-constructing-backlog/references/functional-domains.md) (e.g. search, FAQ, newsletter, map, events, RGPD, translator). Record **hypotheses** for matching plugins and install tasks before other epic mining.
2. **Broader mining:** Goals, decisions, risks, follow-ups; candidate epic groupings and issue **titles** for **custom** domains (CPT-driven, custom technological)—label as hypotheses.
3. Document **gaps** where the transcript is silent or ambiguous.
4. Write the **transcript handoff** per [transcript-handoff.md](../co-constructing-backlog/references/transcript-handoff.md) (transcript source, optional summary, hypotheses—with WonderWp-oriented items listed first when present—gaps).
5. Recommend saving to e.g. `.cursor/backlog/<slug>-transcript-handoff.md`.

**Success criteria:** Handoff document matches the reference and is saved or shown for the user to save.

### Step 4: Next step

**Do:**

1. Tell the user to run **co-constructing-backlog** with this handoff so the full WonderWP analysis (questions + `references/` subprocedures) runs before any title-only backlog draft exists.
2. **Exception:** If the user **explicitly** requests a rough title-only draft **without** the workshop, you may emit a minimal [backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md) **only** with their acknowledgment that depth will be lower than the co-constructing path; still recommend the standard path.

**Success criteria:** Clear handoff to co-constructing (default); optional shortcut documented if chosen.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Reference | [co-constructing-backlog/references/transcript-handoff.md](../co-constructing-backlog/references/transcript-handoff.md) | Output artifact shape (input to co-constructing) |
| Reference | [co-constructing-backlog/references/functional-domains.md](../co-constructing-backlog/references/functional-domains.md) | WonderWp Base catalog and domain order (for transcript mining) |
| Related skill | [co-constructing-backlog](../co-constructing-backlog/SKILL.md) | Full knowledge loop; consumes handoff when present |
| Reference | [building-backlog-plan-from-draft/references/backlog-draft.md](../building-backlog-plan-from-draft/references/backlog-draft.md) | Title-only draft (after co-constructing, unless shortcut) |
| Related skill | [building-backlog-plan-from-draft](../building-backlog-plan-from-draft/SKILL.md) | Enrich draft → enriched plan |
| Related skill | [creating-epics-and-issues-from-backlog-plan](../creating-epics-and-issues-from-backlog-plan/SKILL.md) | Create epics and issues from enriched plan |
