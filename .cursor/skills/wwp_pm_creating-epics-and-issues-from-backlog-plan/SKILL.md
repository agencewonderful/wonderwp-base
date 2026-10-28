---
name: creating-epics-and-issues-from-backlog-plan
description: >-
  Parses an enriched backlog markdown file, validates issue categories against
  agents.md, then creates parent epics and child issues (and unscoped issues)
  in the configured tracker by following creating-an-epic and creating-an-issue.
  Use after building-backlog-plan-from-draft or hand-editing the plan.
metadata:
  domain: wonderwp-product-manager
---

# Creating epics and issues from a backlog plan

## Goal

Read an **enriched backlog plan** markdown document (canonical shape in [references/enriched-backlog-plan.md](references/enriched-backlog-plan.md)), **validate categories** against `agents.md`, resolve issue tooling from `agents.md`, then **create epics first** and **issues second**—linking children to parents—plus **Unscoped** issues last. For each epic follow **creating-an-epic**; for each ticket follow **creating-an-issue** (same contracts as ad-hoc creation). Deliver a summary table with URLs.

## Rules

- **Language (tracker):** Créer epics et issues avec titres et corps **en français**, alignés sur le plan enrichi — [references/artifact-language-fr.md](../../references/artifact-language-fr.md). **Titres d’epics** dans le tracker en **majuscules intégrales** (comme dans le markdown).
- **Provider-agnostic:** resolve issue creation from `./agents.md`; if missing or ambiguous, ask the user (same escape hatch as **create-pull-request** for PR tooling).
- **Delegation:** implement each epic using **creating-an-epic** and each ticket using **creating-an-issue** (including category mapping, parent epic body line, assignee, and `visual_reference` per [creating-an-issue/references/issue-dto.md](../creating-an-issue/references/issue-dto.md)).
- **Category validation:** before calling the tracker API, **validate every ticket `category`** against the allowed list in the **GitHub / issue-tracker configuration section** of `agents.md`. If the list is missing or a value is unknown, **ask the user** and **persist updates to `agents.md`**—do not invent labels or category names.
- **Order of operations:** create all **epics** (`### Epic:`) in document order — including epics that have **no** `#### Ticket:` blocks yet; then create **issues** under each epic that has tickets, with parent references in the body (e.g. `Part of #123`) and native parent/subtask links when supported; finally create **`### Unscoped`** issues.
- **Epic bodies:** compose from `slug`, `summary`, and optional `milestone` / `iteration` lines in the backlog.
- **`NEEDS CLARIFICATION`:** do not create a tracker item with that literal category if your tracker forbids it—either pause for user input or create the issue without a category label until clarified (prefer pausing).

## Instructions

1. Read the markdown file path or paste; parse per [references/enriched-backlog-plan.md](references/enriched-backlog-plan.md).
2. Read `./agents.md` for tooling, scope, and allowed categories; validate every ticket category before creating anything.
3. For each epic in order, run the **creating-an-epic** procedure (title + body from epic metadata).
4. For each `#### Ticket:` under an epic, run the **creating-an-issue** procedure with parent epic reference and mapped `visual_reference` when present.
5. Process `### Unscoped` tickets without a parent epic (still via **creating-an-issue**).
6. Report a summary table with URLs.

## Workflow

### Step 1: Load backlog file

**Do:**

1. Read the markdown path the user provides (or paste).
2. Parse epic sections, nested tickets, and unscoped tickets per [references/enriched-backlog-plan.md](references/enriched-backlog-plan.md).

**Success criteria:** Internal model of epics and tickets is complete.

### Step 2: Resolve tooling and categories

**Do:**

1. Read `./agents.md`; detect issue-creation tool/MCP and scope (repo, project, etc.).
2. Read **allowed ticket categories** from the GitHub / issue-tracker section.
3. If any ticket category fails validation, stop: ask user, update `agents.md`, re-validate—**before** creating issues.

**Success criteria:** Tool is known; every category is allowed or explicitly resolved.

### Step 3: Create epics (parents)

**Do:**

1. For each `### Epic:` in order, create one parent work item following **creating-an-epic** (title + body).
2. Record a map `slug-or-title → issue_id / URL` for linking children.

**Success criteria:** All epics exist; mapping is stored for child creation.

### Step 4: Create epic-scoped issues

**Do:**

1. For each `#### Ticket:` under an epic, create an issue following **creating-an-issue** with:
   - title, body (include epic reference from the map), category mapped per tracker rules
   - assignee if valid for the tool and provided
   - `visual_reference` per [creating-an-issue/references/issue-dto.md](../creating-an-issue/references/issue-dto.md) and `agents.md`
   - labels/priority if the tool supports them
2. Use dependency notes in the body if you cannot set formal dependencies in the tool (`depends_on:`).

**Success criteria:** All scoped issues exist and reference their epic.

### Step 5: Create unscoped issues

**Do:**

1. Process `### Unscoped` tickets the same way without a parent epic line (unless the user asked to attach to a milestone only).

**Success criteria:** All unscoped issues exist.

### Step 6: Report

**Do:**

1. Present a table: **Title | URL | Parent | Assignee | Category**
2. List any skipped or deferred items with reasons.

**Success criteria:** User can verify every created item.

## Resources

| Type      | Path | Description |
| --------- | ---- | ----------- |
| Reference | [references/enriched-backlog-plan.md](references/enriched-backlog-plan.md) | Input markdown shape |
| Reference | [creating-an-issue/references/issue-dto.md](../creating-an-issue/references/issue-dto.md) | Ticket field semantics |
