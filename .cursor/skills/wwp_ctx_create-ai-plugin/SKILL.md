---
name: create-ai-plugin
description: >-
  Scaffolds a new Wonderful AI plugin (domain) folder with the required `manifest/plugin.json` and
  the expected `skills/` structure. Use when you want to add a new plugin under `plugins/`
  (optionally skills-only), so the rest of the architecture can reference its skills.
---

# Create AI Plugin

## Goal

Create the base folder layout for a new Wonderful AI plugin so subsequent skills can be added and used.

## Rules

- Use the repository’s plugin layout conventions: `plugins/<pluginName>/manifest/plugin.json` and `plugins/<pluginName>/skills/<skillFolderName>/SKILL.md`.
- If the plugin is skills-only (no specialist agent), omit the `agents/` directory and omit the `agents` field from `manifest/plugin.json`.

## Quick Start

```bash
# Create the plugin folder structure
mkdir -p "plugins/wonderwp-context-engineer/manifest"
mkdir -p "plugins/wonderwp-context-engineer/skills"

# Add a manifest with a skills glob
# (then create individual skills under plugins/<pluginName>/skills/<skillFolderName>/SKILL.md)
```

## Workflow

```mermaid
flowchart LR
    A[Step 1: Choose plugin name] --> B[Step 2: Create folder layout] --> C[Step 3: Write manifest] --> D[Step 4: Prepare skills dir]
```

### Step 1: Choose plugin name

**Do:**

1. Ask for the desired plugin (domain) name: `plugins/<pluginName>/...`.
2. Ask whether the plugin will be skills-only (no agents) or will also provide `agents/<pluginName>.md`.

**Success criteria:** The plugin name is confirmed and it’s clear whether `agents/` should exist.

### Step 2: Create folder layout

**Do:**

1. Create `plugins/<pluginName>/manifest/`.
2. Create `plugins/<pluginName>/skills/`.
3. If the plugin is not skills-only, create `plugins/<pluginName>/agents/`.

**Success criteria:** The expected directories exist on disk (even if empty).

### Step 3: Write manifest

**Do:**

1. Create `plugins/<pluginName>/manifest/plugin.json`.
2. Set a unique string field `diminutive` (short id, typically like `wwp_pm` or `wwp_theme`). The Cursor installer reads this via `jq` and uses it to prefix flat-installed skills (`.cursor/skills/<diminutive>_<skillFolder>/`) and commands (`.cursor/commands/<diminutive>_<file>`). Values must be **unique across all plugins** in the repo (the install script errors on duplicates). Do not rely on a trailing underscore in JSON; the installer strips trailing `_` and adds its own separator.
3. Set `skills: ["./skills"]` so the installer can expose available skills.
4. If skills-only: omit the `agents` field entirely (and do not create an `agents/` directory).
5. If not skills-only: include an `agents` glob consistent with other plugins.

**Success criteria:** `manifest/plugin.json` is valid JSON, defines a non-empty string `diminutive` that does not collide with other plugins, and correctly advertises `skills` (and `agents`, if present).

### Step 4: Prepare skills dir

**Do:**

1. Ask what the first skill(s) should be.
2. For each skill, create `plugins/<pluginName>/skills/<skillFolderName>/`.
3. Plan to add `SKILL.md` in each skill folder (done by the `create-ai-skill` skill).

**Success criteria:** The `skills/` folder structure is ready for skill authoring.

## Advanced Features

> Optional: link to references for complex features.

- **Manifest schema**: See [plugins/wonderwp-backend/manifest/plugin.json](./#)
- **Skill placement**: See [plugins/<pluginName>/skills/<skillFolderName>/SKILL.md](./#)

## Resources

| Type      | Path                                  | Description |
| --------- | ------------------------------------- | ----------- |
| Reference | `plugins/<pluginName>/manifest/plugin.json` | Plugin-level metadata |
| Reference | `README.md#adding-new-skills`         | Skills folder and `SKILL.md` placement conventions |
