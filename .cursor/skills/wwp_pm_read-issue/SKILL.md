---
name: read-issue
description: >-
  Loads an existing issue from the tracker configured in agents.md (GitHub CLI, MCP, or other), and returns a structured summary for implementation or orchestration.
  Use when fetching ticket details, acceptance criteria, or discussion context for a known issue reference.
metadata:
  domain: wonderwp-product-manager
---

# Read issue

## Goal

Retrieve **one** work item from the project’s issue tracker and return a **structured summary** suitable for development handoff, using field semantics aligned with [references/issue-dto.md](../creating-an-issue/references/issue-dto.md) where applicable.

## Rules

- **Provider-agnostic:** resolve how to read issues from `./agents.md` at the consumer project root; if unclear, ask the user.
- **Language (WonderWP):** any **new narrative** you add (interpretation, recap) follows [references/artifact-language-fr.md](../../references/artifact-language-fr.md); preserve original issue title/body text as returned by the tracker unless the user asks for translation.
- For GitHub-backed projects, **prefer `gh`** for read operations when `agents.md` or team conventions indicate it (see issue-tracker section in consumer `agents.md`, often aligned with [initializing-agents-md references/agents-md-template.md](../../../wonderwp-context-engineer/skills/initializing-agents-md/references/agents-md-template.md) when bootstrapping projects).

## Instructions

### Step 1: Resolve the issue reference

**Do:**

1. Obtain issue **number**, **URL**, or sufficient search context from the user or conversation.
2. Read `./agents.md` for: tracker tool, **default repository / scope**, and any epic/parent conventions.

**Success criteria:** You know which issue to load and in which scope.

### Step 2: Fetch the issue

**Do:**

1. Use the tool indicated by `agents.md` (e.g. `gh issue view <n> --json ...` on GitHub, or MCP/API as configured).
2. Load title, body, state, labels or category fields, assignees, milestone/project if relevant, and parent/epic links when exposed.

**Success criteria:** Raw issue payload is available.

### Step 3: Summarise for implementation

**Do:**

1. Emit a structured summary. Map fields to the spirit of [references/issue-dto.md](../creating-an-issue/references/issue-dto.md): `title`, `body` (or shortened sections with pointers), `category` (from labels/components if applicable), `assignee`, `parent_epic_ref`, `visual_reference` when detectable in body or attachments.
2. Add: **state**, **URL**, tracker id, **acceptance signals** (explicit AC, tasks, checklists), and **comment highlights** only if needed for implementation (decisions, blockers).
3. Note gaps or ambiguities (e.g. missing category) without inventing tracker data.

**Success criteria:** The user or orchestrator can plan or implement without re-fetching the issue unnecessarily.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Reference | [references/issue-dto.md](../creating-an-issue/references/issue-dto.md) | Canonical field meanings for issues |
