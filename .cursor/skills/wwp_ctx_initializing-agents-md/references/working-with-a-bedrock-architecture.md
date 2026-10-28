# Working with a Bedrock architecture

## Goal

Recognize a Roots Bedrock WordPress layout and use the correct `web/app/…` and `web/wp` paths instead of classic `wp-content` assumptions when writing skills, scripts, or rules.

## Rules

### Detecting Bedrock

To determine if the project uses a Roots Bedrock architecture:

- Inspect the project’s `composer.json` (typically at the repository root).
- If it contains a reference to `"roots/bedrock-autoloader"`, the project is using Bedrock.

### Bedrock directory layout

In Bedrock, WordPress directories are organized differently from a classic install. The canonical paths are:

- **Must-use plugins:**  
  `web/app/mu-plugins`
- **Plugins:**  
  `web/app/plugins`
- **Themes:**  
  `web/app/themes`
- **Uploads:**  
  `web/app/uploads`
- **WordPress core directory:**  
  `web/wp`

When writing rules, scripts, or skills for this project, always assume these locations instead of the default `wp-content` layout.

## Workflow

### Step 1: Confirm Bedrock and apply paths

**Do:**

1. Verify Bedrock using the **Rules** above (`composer.json` + `roots/bedrock-autoloader`).
2. For any path in documentation, skills, or automation steps, substitute Bedrock paths (see **Rules – Bedrock directory layout**) for default `wp-content` layouts.

**Success criteria:** Paths in outputs match Bedrock (`web/app/...`, `web/wp`) when this project is Bedrock-based.

## Advanced Features

### Example – Locating a plugin

Instead of `wp-content/plugins/my-plugin`, use:

```text
web/app/plugins/my-plugin
```

### Example – Locating the active theme

Instead of `wp-content/themes/my-theme`, use:

```text
web/app/themes/my-theme
```

### Follow-ups

Combine this skill with domain-specific ones (backend, block editor, theme, project) to:

- Resolve correct file paths.
- Configure build tools.
- Place overrides and new code in the right directories for Bedrock projects.
