---
name: adding-a-theme-stylesheet-to-an-existing-block
description: >-
  Adds a theme-level SCSS stylesheet for an existing block in a WonderWP
  child theme, ensuring the correct folder structure, verifying the block
  exists, and compiling the theme assets. Use when the user wants to style an
  existing block from the theme rather than the plugin.
metadata:
  domain: wonderwp-theme
---

# Creating a theme stylesheet for an existing block

## Goal

Add or update a theme-level SCSS stylesheet for an existing block under the child theme’s `block-stylesheets` tree, verify the block exists, compile theme assets, and document the change.

## Rules

- **Mandatory inputs:**
  - The block type to style: must be `namespace/blockname` format (e.g. `core/paragraph`, `my-plugin/card`). If the user does not provide a slash, ask them to specify the namespace and blockname.
  - The target theme: by default, use the current active theme; in many projects this is `wwp_child_theme`.
- Confirm the exact theme path if there is any ambiguity.

## Workflow

### Step 1: Locate the block-stylesheets root

**Do:**

1. In the theme, block stylesheets are expected under:

```text
web/app/themes/<theme>/assets/raw/scss/block-stylesheets
```

2. Check that `block-stylesheets` exists:
   - If not, warn the user: the theme is missing the expected SCSS structure.

**Success criteria:** Expected `block-stylesheets` directory exists or the user is warned.

### Step 2: Verify the block exists

**Do:**

Before creating a stylesheet, confirm the block type exists in the site:

```bash
wp wwp-gutenberg-utils:check-block-exists --block-type=<block-type>
```

- If the block does not exist, warn the user and stop.

**Success criteria:** CLI confirms the block exists or the workflow stops with a clear warning.

### Step 3: Create namespace and block folders

**Do:**

Given a valid block type:

1. Split on `/`:
   - Namespace: part before the slash.
   - Block name: part after the slash.
2. Under `block-stylesheets`, ensure:
   - A folder exists for the namespace.
   - Inside it, a folder exists for the block name.
3. Within the block name folder:
   - Check for `styles.scss`:
     - If it exists, inform the user and provide the path.
     - If it does not exist, create it and provide the path.

This yields a path like:

```text
web/app/themes/<theme>/assets/raw/scss/block-stylesheets/<namespace>/<block-name>/styles.scss
```

**Success criteria:** `styles.scss` is present at the correct path (new or existing).

### Step 4: Compile theme assets

**Do:**

Once the stylesheet file is created or updated:

1. From the project root, run the theme build command:

```bash
npm run build:theme
```

2. Ensure:
   - The SCSS changes are compiled into the theme’s built CSS.
   - No build errors occur.

**Success criteria:** Theme build completes without errors and includes the new SCSS.

### Step 5: Document and ship

**Documentation:**

- At the theme root, look for `README.md`:
  - If it exists:
    - Locate or create a “Theme Block Stylesheet” section.
    - Add a line describing the new block stylesheet and its path.
  - If not, create a minimal README with:
    - A brief theme description.
    - A “Theme Block Stylesheet” section listing the new stylesheet.

**Proposing a commit:**

- Once the stylesheet is in place and compiled:
  - Propose a commit summarizing:
    - The new or updated SCSS file.
    - Any README changes.
  - Provide the corresponding `git commit` command.

**Success criteria:** Theme README documents the stylesheet; commit message proposed.

## Advanced Features

### Example – Styling `core/query` in the child theme

User intent:

> \"Add theme styles for the `core/query` block in my child theme.\" 

Flow:

1. Confirm theme (e.g. `wwp_child_theme`) and block type `core/query`.
2. Verify the block exists with the CLI command.
3. Create or reuse:

```text
web/app/themes/wwp_child_theme/assets/raw/scss/block-stylesheets/core/query/styles.scss
```

4. Run `npm run build:theme`.
5. Document the new stylesheet in the theme README’s “Theme Block Stylesheet” section.

### Follow-ups

After adding a stylesheet for one block, users may:

- Add stylesheets for other blocks in the same theme.
- Pair these styles with block variations or style variations defined in the block editor skills.

Ask which blocks they want to theme next and reuse this pattern.
