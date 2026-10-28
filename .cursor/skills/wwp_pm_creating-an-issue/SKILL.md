---
name: creating-an-issue
description: >-
  Creates an issue (child or standalone) with title, body, and category,
  optionally assigned and linked to a parent epic, using the tracker configured
  in agents.md. Use when filing one structured issue with a required category.
metadata:
  domain: wonderwp-product-manager
---

# Creating an issue

## Goal

Create one tracker work item with **title**, **body**, and **category**, mapping category to labels, components, or custom fields per the active tool, and optionally set **assignee**, **parent epic** reference, and **visual reference**—after resolving tooling and **allowed categories** from `agents.md`.

## Rules

- **Language (WonderWP):** Titres et corps d’issue par défaut en **français** — [references/artifact-language-fr.md](../../references/artifact-language-fr.md) ; l’utilisateur peut imposer une autre langue explicitement.
- **Provider-agnostic:** discover issue creation from `./agents.md`; if unclear, ask the user.
- **Categories:** map `category` to whatever the tracker supports (label, component, custom field, etc.). If the team uses labels only, a common pattern is `category:<name>` **only when `<name>` is an allowed value**.
- **Allowed category list:** if the agent does **not** already know the canonical values (including a list under the **GitHub / issue-tracker configuration section** in `agents.md`), **stop and ask the user** for the list or a link to team conventions. After the user provides it, **append or update that list in `agents.md`** in the same section so future runs do not re-ask.
- **Do not invent** category names: if a value is not in the allowed list, ask or mark for clarification rather than guessing.
- **Parent epic:** when creating a child issue, include a clear reference in the body (e.g. `Part of #42`) and use subtask/parent APIs when the tool exposes them.
- **Visual reference:** when provided, map per [references/issue-dto.md](references/issue-dto.md) and `agents.md` (body section, link, or attachment workflow).

## Instructions

1. Read `./agents.md` for tracker tool, scope, and allowed categories.
2. Collect fields per [references/issue-dto.md](references/issue-dto.md): title, body, category; optionally assignee, parent epic ref, visual reference.
3. Validate category against `agents.md`; update `agents.md` with the category list if missing and the user supplies it.
4. Map category (and labels if any) to tracker fields; include parent epic line in body when applicable; include visual reference in body or tracker-specific field per conventions.
5. Create the issue via the configured tool and return URL, ID, assignee confirmation, and applied labels/category.

## Workflow

### Step 1: Gather fields

**Do:**

1. Collect **title** and **body**.
2. Collect **category**; validate against the allowed list in `agents.md` when present.
3. If the allowed list is missing from `agents.md`:
   - ask the user for the canonical categories (or documentation link)
   - then **record them under the GitHub / issue-tracker configuration section** in `agents.md`
4. Optionally collect **assignee** (username, email, or handle as required by the tool), **parent epic** reference (number, URL, or ID), and **visual reference** (screenshot path, Figma URL) per [references/issue-dto.md](references/issue-dto.md).

**Success criteria:** All required fields are known; category is allowed or explicitly exempted by user update to `agents.md`.

### Step 2: Resolve tooling from `agents.md`

**Do:**

1. Read `./agents.md`.
2. Identify the issue-creation tool/MCP and required scope (repository, project, etc.).

**Success criteria:** You can invoke the correct create-issue operation.

### Step 3: Map category to tracker fields

**Do:**

1. Apply the category per tracker capabilities (single label, combined labels, component, custom field).
2. Add parent reference to the body when applicable.
3. Add visual reference to the body or appropriate tracker field per [references/issue-dto.md](references/issue-dto.md).

**Success criteria:** Create payload includes category mapping consistent with project conventions.

### Step 4: Create and confirm

**Do:**

1. Create the issue via the configured tool.
2. Return **URL**, **ID**, assignee confirmation (if set), and applied category/labels.

**Success criteria:** Issue exists and the user has a direct link.

## Advanced Features

- If the user has no `agents.md` yet, offer a minimal template snippet including an **Allowed ticket categories** list under the tracker section.

## Resources

| Type      | Path | Description |
| --------- | ---- | ----------- |
| Reference | [references/issue-dto.md](references/issue-dto.md) | Canonical issue fields and markdown shape for enriched plans |
