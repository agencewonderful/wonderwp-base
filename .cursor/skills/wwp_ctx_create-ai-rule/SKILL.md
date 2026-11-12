---
name: create-ai-rule
description: >-
  Scaffolds an IDE-agnostic rule (.md) under a Wonderful AI plugin's rules/
  folder and wires the manifest rules field. Use when adding standing
  assistant constraints that should install via install.sh (Cursor → .mdc).
---

# Create AI Rule

## Goal

Add a portable `.md` rule in the correct domain plugin so `scripts/install.sh` can distribute it (Cursor as `.mdc`, Claude as `.md` in the plugin tree).

## Rules

- Source of truth is always **`.md`**, never hand-authored `.mdc` in the framework repo.
- Place rules at `plugins/<pluginName>/rules/<rule-slug>.md`.
- Ensure `manifest/plugin.json` includes `"rules": ["./rules"]` when the plugin ships installable harness rules.
- Frontmatter (portable): `description` required; `globs` and `alwaysApply` optional.
- Keep wording **project-agnostic** (no consumer project names or site-specific prefixes).
- Do not use `rules/` for installable harness rules when the folder already holds internal domain specs without a `"rules"` manifest entry (e.g. design-system contract docs) — either keep them undeclared or use a separate path.

## Workflow

### Step 1: Choose plugin and slug

**Do:**

1. Ask which domain plugin owns the constraint (`wonderwp-theme`, `wonderwp-backend`, …).
2. Ask for a kebab-case `rule-slug` and whether it should `alwaysApply`.

**Success criteria:** Plugin path and slug confirmed.

### Step 2: Create the rule file

**Do:**

1. Create `plugins/<pluginName>/rules/` if missing.
2. Write `plugins/<pluginName>/rules/<rule-slug>.md` with YAML frontmatter + markdown body.

Example:

```markdown
---
description: Short standing constraint for assistants.
globs: "**/path/pattern/**"
alwaysApply: false
---

# Title

Constraint body…
```

Prefer targeted `globs` over `alwaysApply: true` unless the constraint must apply in every chat turn.

**Success criteria:** File exists with portable frontmatter.

### Step 3: Wire the manifest

**Do:**

1. Open `plugins/<pluginName>/manifest/plugin.json`.
2. Add `"rules": ["./rules"]` if absent.

**Success criteria:** Manifest advertises rules for the installer.

### Step 4: Install locally (optional)

**Do:**

From the consumer project root:

```bash
./.wonderful/scripts/install.sh cursor
```

**Success criteria:** `.cursor/rules/<diminutive>_<rule-slug>.mdc` appears for Cursor.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Example | `plugins/wonderwp-theme/rules/no-business-logic-in-theme.md` | Theme vs business boundary |
| Example | `plugins/wonderwp-backend/rules/no-hooks-or-business-in-manager.md` | Manager boundaries |
| Script | `scripts/install.sh` | Provider-aware install including rules |
