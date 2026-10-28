---
name: create-ai-skill
description: >-
  Creates a new Wonderful AI skill folder and `SKILL.md` file with the required metadata and
  structure. Use when you want to add a new task/procedure that should live under
  `plugins/<pluginName>/skills/<skillFolderName>/SKILL.md` and optionally be advertised by the plugin agent.
---

# Create AI Skill

## Goal

Add a new `SKILL.md` for a specific task under the correct plugin `skills/` folder, following the repository’s required conventions.

## Rules

- Always create a dedicated skill folder: `plugins/<pluginName>/skills/<skillFolderName>/`.
- Copy [resources/skill-template.md](./resources/skill-template.md) as the structural source of truth: `#` title, **`## Goal`**, **`## Rules`** (required); optional **`## Workflow`** with **Do** / **Success criteria** steps; optional **Advanced Features** and **Resources**.
- YAML frontmatter: at minimum `name` and `description` (use **Use when …** in `description` for implicit routing); optional `metadata.domain`.
- If the target plugin has an agent definition, update its `## Available Skills` list to link to the new `SKILL.md`.

## Quick Start

```bash
# 1) Create the skill folder
mkdir -p "plugins/<pluginName>/skills/<skillFolderName>"

# 2) Create SKILL.md in that folder (include YAML frontmatter + required headings)
touch "plugins/<pluginName>/skills/<skillFolderName>/SKILL.md"
```

## Workflow

```mermaid
flowchart LR
    A[Step 1: Identify target plugin and skill name] --> B[Step 2: Create folder + SKILL.md] --> C[Step 3: Add required structure] --> D[Step 4: Wire into agent (if any)]
```

### Step 1: Identify target plugin and skill name

**Do:**

1. Ask which plugin/domain the skill belongs to: `plugins/<pluginName>/`.
2. Ask for the desired skill folder name: `skills/<skillFolderName>/`.
3. Ask for the user intent / task the skill should cover.

**Success criteria:** Plugin name, skill folder name, and the task description are confirmed.

### Step 2: Create folder + SKILL.md

**Do:**

1. Create `plugins/<pluginName>/skills/<skillFolderName>/`.
2. Create `plugins/<pluginName>/skills/<skillFolderName>/SKILL.md`.
3. Copy the skill template from this plugin’s `resources/` so the structure stays consistent.

**Success criteria:** `SKILL.md` exists at the expected path and matches the template’s required layout.

### Step 3: Add required structure

**Do:**

1. Fill YAML frontmatter (`name`, `description` at minimum; optional `metadata.domain`).
2. Keep the template sections: `#` title, `## Goal`, `## Rules`; extend with `## Workflow` (step tables) when the procedure is multi-step.
3. Regenerate the repo catalogue: run `python3 scripts/generate-skills-index.py` from the `ai-rules` root (adds/updates `skills-index.json`).

**Success criteria:** The skill matches the template’s required headings and reads as a single actionable procedure.

### Step 4: Wire into agent (if any)

**Do:**

1. Check whether the target plugin has an agent file at `plugins/<pluginName>/agents/<pluginName>.md`.
2. If it exists:
   - add a new entry under `## Available Skills`
   - following the existing link style used in the agent.
3. If it does not exist (skills-only plugin), skip agent wiring.

**Success criteria:** The new skill is discoverable in the plugin’s agent (when an agent exists).

## Advanced Features

> Optional: link to references for complex features.

- **Skill template**: See [plugins/wonderwp-context-engineer/skills/create-ai-skill/resources/skill-template.md](./#)
- **Skill placement**: See [README.md#adding-new-skills](./#)

## Resources

| Type      | Path                                                          | Description |
| --------- | ------------------------------------------------------------- | ----------- |
| Template  | `resources/skill-template.md`                               | The always-follow skill markdown template |
| Reference | `README.md#adding-new-skills`                                 | Folder layout + minimum `SKILL.md` requirements |
| Reference | `skills-index.json` (repo root)                             | Regenerated catalogue for tooling; run `scripts/generate-skills-index.py` |
| Reference | `plugins/<pluginName>/agents/<pluginName>.md#available-skills` | How skills are advertised in an agent |
