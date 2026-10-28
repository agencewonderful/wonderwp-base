# Issue DTO

Canonical fields for a single work item (issue/ticket) when creating or serializing it. Used by **creating-an-issue** for one-off creation and by **creating-epics-and-issues-from-backlog-plan** for each `#### Ticket:` block in an enriched backlog plan.

**WonderWP:** `title` and `body` are **in French** in project artefacts unless the user specifies otherwise — [artifact-language-fr.md](../../../references/artifact-language-fr.md).

## Fields

| Field | Required | Description |
| ----- | -------- | ----------- |
| `title` | Yes | Issue title in the tracker. |
| `body` | Yes | Description, acceptance notes, context. May include a **Visual reference** subsection when `visual_reference` is set (see mapping below). |
| `category` | Yes for this skill | Allowed value from `agents.md`, or `NEEDS CLARIFICATION` when unresolved. |
| `assignee` | No | Username, email, or handle as required by the tool. |
| `parent_epic_ref` | No | Epic issue number, URL, or ID when this issue is a child of an epic. |
| `visual_reference` | No | Screenshot path, Figma URL, or other link for design/UI context. |

Optional fields often present in backlog markdown (map when the tracker supports them): `labels`, `priority`, `depends_on` — see [enriched-backlog-plan.md](../../creating-epics-and-issues-from-backlog-plan/references/enriched-backlog-plan.md).

## Markdown serialization

In an enriched backlog plan, a ticket is represented as:

```markdown
#### Ticket: <title>
- category: <value or NEEDS CLARIFICATION>
- body: |
    <multiline body>
- assignee: <handle or NEEDS CLARIFICATION>
- visual_reference: <url or path or NONE>
```

Use `- visual_reference: NONE` or omit the line when there is no visual reference.

Example (French):

```markdown
#### Ticket: Intégration du menu mobile
- category: Front
- body: |
    Comportement responsive, accessibilité, tests navigateurs.
- assignee: NONE
- visual_reference: NONE
```

## Mapping `visual_reference` to the tracker

Follow `./agents.md` and tracker capabilities:

- **GitHub / similar:** append a **Visual reference** section to the issue body, or add a link line; attachments may require a separate upload step if the value is a local file path.
- **Do not invent** attachment APIs; ask the user when the tool requires manual upload.
