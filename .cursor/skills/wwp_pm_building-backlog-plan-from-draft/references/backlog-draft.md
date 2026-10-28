# Backlog draft (input artifact)

Title-only backlog consumed by **building-backlog-plan-from-draft** for interactive enrichment.

**When it is produced:** Prefer **co-constructing-backlog** (live workshop or transcript-informed session after **planning-backlog-from-conversation-transcript**). The canonical path is: optional transcript handoff → **co-constructing-backlog** → this draft shape. A title-only draft may also be emitted without the workshop only when the user explicitly opts into the shortcut described in the planning skill.

## Structure

1. An H1 **document title** and optional short context paragraph.
2. For each epic, a heading: `### Epic: <TITLE>` — the epic name is **full uppercase** (French Unicode uppercase), per [artifact-language-fr.md](../../../references/artifact-language-fr.md).
   - Under the epic, list planned work as **plain bullet lines** — each bullet is one **issue title** only (no `#### Ticket:` blocks yet):
     - `- <issue title>`
     - `- <another issue title>`
   - An epic **may** have **no bullets yet** (placeholder epic, e.g. scope to be defined in the workshop). **building-backlog-plan-from-draft** still emits `slug` / `summary` for that epic with no `#### Ticket:` blocks underneath until issues are added.
3. Optional section for work not tied to an epic: `### Unscoped` then the same pattern of `- <issue title>` bullets.

Do not add `category`, `body`, `assignee`, or `visual_reference` at this stage. The draft uses **epic title + issue title bullets only**. **building-backlog-plan-from-draft** adds required epic metadata (`slug`, `summary`) when emitting the enriched file—**without** an interactive epic interview: derive `slug` from the epic title (kebab-case) and a one-line `summary` from the epic title or issue list; use `NEEDS CLARIFICATION` only if derivation is impossible.

**Language (WonderWP):** Titres d’epics en **français, majuscules intégrales** ; titres d’issues en **français** (casse naturelle) — [artifact-language-fr.md](../../../references/artifact-language-fr.md).

## Example

```markdown
# Backlog — Livraison T2

### Epic: PARCOURS DE COMMANDE
- Bloc estimateur de livraison
- Raccord estimateur au template panier

### Epic: ÉDITORIAL
- Bloc biographie auteur

### Epic: PAGE D’ACCUEIL

### Unscoped
- Mise à jour PHP sur la préproduction
```

## Output contract

The enrichment skill expands each `- <issue title>` into a full `#### Ticket:` block matching [enriched-backlog-plan.md](../../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md). Epics with **no** issue lines produce **only** `slug` / `summary` lines until tickets exist. Ticket fields align with [creating-an-issue/references/issue-dto.md](../../creating-an-issue/references/issue-dto.md).
