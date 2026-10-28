# Enriched backlog plan (input artifact)

Markdown document consumed by **creating-epics-and-issues-from-backlog-plan**. It is produced by **building-backlog-plan-from-draft** (interactive enrichment) or written by hand. Epics and tickets are created in the PM tool configured in `./agents.md`.

**Language (WonderWP):** Text in this file (`summary`, `body`, issue titles) is **in French** — [artifact-language-fr.md](../../../references/artifact-language-fr.md). **Epic titles** in `### Epic:` lines are **full uppercase** (same reference).

Ticket-level fields match the logical shape in [creating-an-issue/references/issue-dto.md](../../creating-an-issue/references/issue-dto.md).

## Document structure

1. An H1 **document title** and optional introductory paragraph (context, date, participants).
2. For each epic, a heading: `### Epic: <TITLE>` (full uppercase)
   - Bullets under it:
     - `- slug: <short-kebab-id>`
     - `- summary: <one-line epic scope>`
     - Optional: `- milestone: <free text>` or `- iteration: <free text>`
3. Under each epic, for each issue (if any): `#### Ticket: <title>`
   - Bullets:
     - `- category: <value from allowed list in agents.md, or NEEDS CLARIFICATION>`
     - `- body: |` then indented multi-line body **or** `- body: <single-line summary>` if short
     - Optional: `- assignee: <handle or NEEDS CLARIFICATION>`
     - Optional: `- visual_reference: <url, path, or NONE>` — see [issue-dto.md](../../creating-an-issue/references/issue-dto.md)
     - Optional: `- labels: <comma-separated or NONE>`
     - Optional: `- priority: <low|medium|high|critical or NEEDS CLARIFICATION>`
     - Optional: `- depends_on: <comma-separated ticket titles or slugs>`
4. **Orphan tickets** (no epic): use heading `### Unscoped` then the same `#### Ticket:` blocks as above.

Use consistent indentation for multiline `body: |` blocks (e.g. two or four spaces for continuation lines).

## Example (fragment, French)

```markdown
### Epic: HEADER
- slug: header
- summary: Template part et navigation du site.

#### Ticket: Header / Intégration du menu mobile
- category: Front
- body: |
    Reprise maquettes, breakpoints, tests.
- visual_reference: NONE
```

## Orchestration note

When materializing this file in the tracker, apply **creating-an-epic** for each `### Epic:` block and **creating-an-issue** for each `#### Ticket:` block so behavior matches one-off creation. Epics with **no** tickets still get an epic in the tracker; add issues later when the draft gains bullets.
