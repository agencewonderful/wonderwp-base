---
name: setting-up-block-compilation
description: >-
  Sets up block compilation for a plugin by creating src/blocks, a
  webpack.config.js that extends @wordpress/scripts, and project-level build
  scripts and manager config. Use when a plugin needs to build Gutenberg blocks
  but has no compilation setup yet.
metadata:
  domain: wonderwp-block-editor
---

# Setting up block compilation

## Goal

Configure block compilation for a WonderWP plugin that must build Gutenberg blocks but has no build pipeline yet.

## Rules

- **Mandatory input:** target plugin (path / slug) where block compilation should be enabled.
- Block compilation is orchestrated from the **project root** `package.json`, not from the plugin alone.
- Do not modify other scripts in root `package.json` unless necessary.

## Workflow

> Use this workflow when a plugin does not yet have block compilation configured and needs to build Gutenberg blocks.

### Step 1: Create the `src/blocks` folder

**Do:**

1. In the plugin directory, ensure `src/blocks` exists:
   - If not, create it.
2. All block source code will live under this folder.

**Success criteria:** `src/blocks` exists under the plugin.

### Step 2: Create `webpack.config.js`

**Do:**

1. At the root of the plugin, check for `webpack.config.js`:
   - If it exists, review before modifying.
   - If it does not exist, create it from [assets/webpack.config.js.tpl](assets/webpack.config.js.tpl).

2. Leave room later to add specific entries for `block-editor` bundles if needed (e.g. for variations/extensions).

**Success criteria:** Plugin has a `webpack.config.js` extending `@wordpress/scripts` as above (or reviewed if pre-existing).

### Step 3: Add build scripts to the project root `package.json`

**Do:**

1. Open the root `package.json` of the project (not the plugin’s).
2. In the `scripts` section, add entries from [assets/package-json-scripts.json.tpl](assets/package-json-scripts.json.tpl).

Replace:

- `<plugin-slug>` with the plugin’s slug (e.g. `my-plugin`).
- `my/plugin/path` with the actual plugin path relative to the project root.

Do not modify other scripts unless necessary.

**Success criteria:** Root `package.json` includes `start:<plugin-slug>` and `build:<plugin-slug>` with correct paths.

### Step 4: Configure the plugin manager

**Do:**

In the plugin’s manager class (typically in the plugin `includes` folder):

1. Locate the `register()` method.
2. Add the configuration from [assets/manager-blocks-config.php.tpl](assets/manager-blocks-config.php.tpl).

This allows the plugin to:

- Know where compiled blocks live.
- Find the `blocks-manifest.php` file created by the build.

**Success criteria:** Manager sets `path.blocks.build` and `blocks.manifest.path` as shown.

### Step 5: Document and ship

**Documentation:**

- Update the plugin README:
  - Add or update a “Blocks” section.
  - Mention:
    - Blocks should be developed in `src/blocks`.
    - Compiled into `build` using:

    ```bash
    npm run start:<plugin-slug>
    npm run build:<plugin-slug>
    ```

**Proposing a commit:**

- Once the configuration is in place and a test build runs successfully:
  - Propose a commit summarizing:
    - New `webpack.config.js`.
    - Root `package.json` script additions.
    - Manager configuration changes.
    - README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README documents development and build commands; commit covers webpack, scripts, manager, and README.

## Advanced Features

### Example – Enabling compilation for `wwp-gutenberg-utils`

Flow:

1. Create `src/blocks` in the `wwp-gutenberg-utils` plugin folder.
2. Add `webpack.config.js` from the template.
3. In the project root `package.json`, add build scripts with paths `web/app/plugins/wwp-gutenberg-utils/...`.
4. In the plugin manager, set `path.blocks.build` and `blocks.manifest.path`.
5. Run `npm run build:wwp-gutenberg-utils` and confirm the build output and manifest are generated.

### Follow-ups

Once compilation is set up, other block-editor skills (creating blocks, variations, extensions, query loop cards, etc.) can rely on this foundation to build and register new block code.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/webpack.config.js.tpl](assets/webpack.config.js.tpl) | Webpack config extending @wordpress/scripts |
| Template | [assets/package-json-scripts.json.tpl](assets/package-json-scripts.json.tpl) | Root package.json build scripts |
| Template | [assets/manager-blocks-config.php.tpl](assets/manager-blocks-config.php.tpl) | Manager block build path config |
