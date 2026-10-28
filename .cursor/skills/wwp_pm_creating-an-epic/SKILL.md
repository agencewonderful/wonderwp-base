---
name: creating-an-epic
description: >-
  Creates an epic as a parent work item in the project’s configured issue tracker,
  after resolving the tool from agents.md. Use when the user wants a top-level
  grouping issue, a parent for future tickets, or to start an epic before child tasks.
metadata:
  domain: wonderwp-product-manager
---

# Creating an epic

## Goal

Create a **parent** work item (epic) using the issue-creation capability configured in the project’s `agents.md`, and return its identifier and URL so child tickets can reference it.

## Rules

- **Language (WonderWP):** Titres et corps d’epic par défaut en **français** — [references/artifact-language-fr.md](../../references/artifact-language-fr.md). **Titre d’epic** : **majuscules intégrales** dans le tracker, aligné sur les artefacts ; l’utilisateur peut imposer une autre langue ou casse explicitement.
- Remain **provider-agnostic**: do not assume GitHub unless `agents.md` points to a GitHub tool or MCP.
- An epic is a **parent**; downstream tickets should reference this item in their body (e.g. `Part of #123`) or via the tracker’s native parent/subtask link when the API supports it.
- If `agents.md` is missing or does not describe how to create issues: stop and ask the user for the correct configuration or file path.

## Workflow

### Step 1: Collect epic content

**Do:**

1. Gather a **title** and **body** (summary, scope, success criteria, or links to specs).
2. If the user did not specify a repository or project context, ask for whatever the configured tool requires (owner/repo, project ID, etc.).

**Success criteria:** Title and body are ready, plus any required tracker context.

### Step 2: Resolve issue-creation tooling from `agents.md`

**Do:**

1. Read `./agents.md` at the project root (or the path the user gives).
2. Locate the section for the **issue tracker** (often nested under GitHub or a similarly named configuration block).
3. Identify:
   - which tool or MCP server creates issues
   - required parameters (repository, organization, default labels, etc.)

**Success criteria:** You know exactly which tool to call to create an issue/task.

### Step 3: Create the parent epic

**Do:**

1. Use the detected tool to create a single work item representing the epic.
2. If the tracker supports a dedicated “epic” type or label (and the user wants it), apply it per project convention from `agents.md`; otherwise a normal issue with a clear title (e.g. prefix `[Epic]`) is acceptable.
3. Capture the returned **identifier** and **URL**.

**Success criteria:** The epic exists in the tracker; identifier and URL are confirmed.

### Step 4: Hand off for child tickets

**Do:**

1. Tell the user the epic reference for child tickets.
2. Suggest using **creating-an-issue** for children, passing the parent reference when creating each issue.

**Success criteria:** User can link new tickets to this epic.

## Advanced Features

- **GitHub:** When `agents.md` indicates GitHub, creating the epic is typically “create issue” with title/body; child issues repeat with `Part of #<epic>` (or sub-issue features if enabled for the repo).
