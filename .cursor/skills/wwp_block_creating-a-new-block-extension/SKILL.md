---
name: creating-a-new-block-extension
description: >-
  Creates a new block extension for the editor, including JS structure for
  controls, attributes, and optional supports, plus build wiring. Use when the
  user wants to extend blocks with custom controls/attributes via block-editor
  filters.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new block extension

## Goal

Create a block editor extension (attribute, HOC control, optional support) under `src/block-editor/block-extensions` and wire it through webpack, the extensions index, and PHP asset enqueuing.

## Rules

- **Mandatory inputs:** extension title (for naming and documentation); target plugin.
- **Clarifications:**
  - Is the extension targeting a specific block?
    - If yes, ask for the block name (e.g. `core/query`).
  - Does the extension require a dedicated support flag?
    - If yes, ask:
      - Support name (e.g. `my-extension-support`).
      - Whether this support already exists or needs to be created.

## Workflow

### Step 1: File and folder structure

**Do:**

In the plugin:

1. Ensure `src/block-editor` exists; create if needed.
2. Ensure `src/block-editor/block-extensions` exists; create if needed.
3. Ensure `src/block-editor/block-extensions/index.js` exists; create if needed.
4. Derive a slug from the extension title (e.g. `my-extension`).
5. Ensure `src/block-editor/block-extensions/<block-extension-slug>` exists; create if needed.
6. Ensure the following files exist (generate if missing):
   - `src/block-editor/block-extensions/<block-extension-slug>/<block-extension-slug>-extension.js`
   - `src/block-editor/block-extensions/<block-extension-slug>/<block-extension-slug>-control.js`
   - `src/block-editor/block-extensions/<block-extension-slug>/<block-extension-slug>-attribute.js`
   - Optionally: `src/block-editor/block-extensions/<block-extension-slug>/<block-extension-slug>-support.js`
7. Ensure `webpack.config.js` exists for the plugin:
   - If not, follow *setting-up-block-compilation*.

**Success criteria:** Extension folder and core JS files exist; webpack config present or compilation skill applied.

### Step 2: Block extension entry file

**Do:**

In `<slug>-extension.js`, copy from [assets/block-extension.js.tpl](assets/block-extension.js.tpl) and wire filters.

Adapt:

- `<ExtensionCamelCase>` to a CamelCase version of the slug.
- Hook names to your plugin slug and extension slug.
- Omit the support section if no support flag is needed.

**Success criteria:** Extension entry registers attribute, control, and optional support filters.

### Step 3: Control file

**Do:**

In `<slug>-control.js`, start from [assets/block-extension-control.js.tpl](assets/block-extension-control.js.tpl).

**With support constraint:** gate with `hasBlockOrActiveVariationSupport` from `wwp-gutenberg-utils` when the extension depends on a support flag.

**Targeting a specific block:** add `if (props.name !== 'core/query') return <BlockEdit {...props} />;` before rendering controls.

Combine support checks and block targeting as needed.

**Success criteria:** Control HOC matches targeting (block name and/or support) as designed.

### Step 4: Attribute file

**Do:**

In `<slug>-attribute.js`, start from [assets/block-extension-attribute.js.tpl](assets/block-extension-attribute.js.tpl).

For support-based targeting, use `hasBlockOrAnyVariationSupport` instead of a block name check.

**Success criteria:** Attribute is registered only for intended blocks or supports.

### Step 5: Support file (optional)

**Do:**

If the extension adds a support flag to blocks, copy [assets/block-extension-support.js.tpl](assets/block-extension-support.js.tpl) to `<slug>-support.js`.

**Success criteria:** Support is added only where intended (or step skipped if no support).

### Step 6: Build integration

**Do:**

1. In the plugin’s `webpack.config.js`, ensure the `entry` includes:

```js
entry: {
  // ...
  "js/block-editor": path.resolve(__dirname, "src/block-editor/index.js"),
  // ...
}
```

2. In `src/block-editor/block-extensions/index.js`:
   - If no extension section exists, add:

   ```js
   /**
    * Block extensions
    *
    * This file is used to register block extensions for the block editor.
    */
   ```

   - Add an import for the new extension under that comment, with a short description:

   ```js
   // My custom block extension
   import "./<block-extension-slug>/<block-extension-slug>-extension";
   ```

3. Ensure `src/block-editor/index.js` imports the extensions index if required by your setup.

4. Check or create `includes/Hooks/BlockEditorAssetsEnqueuer.php` from [assets/BlockEditorAssetsEnqueuer.php.tpl](assets/BlockEditorAssetsEnqueuer.php.tpl) to enqueue `build/js/block-editor.js` and `build/css/block-editor.css`.

**Success criteria:** Bundle builds; extension is imported; editor assets load on admin.

### Step 7: Document and ship

**Documentation:**

- In the plugin `README.md`:
  - Add or update a “Block Extensions” or “Block Variations” section.
  - Describe:
    - The purpose of the extension.
    - Which blocks or supports it targets.

**Proposing a commit:**

- After building and confirming the extension works:
  - Propose a commit summarizing:
    - New extension JS files.
    - Webpack/entry/asset enqueuer updates.
    - README changes.
  - Provide the corresponding `git commit` command.

**Success criteria:** README describes the extension; commit covers JS, build, enqueue, README.

## Advanced Features

### Example – Query support toggle extension

User intent:

> \"I want a control that toggles a custom query behavior on `core/query`.\" 

Flow:

1. Create a `query-toggle` extension folder and files as described above.
2. In the control, gate on `props.name === 'core/query'` and a custom support flag.
3. In the attribute file, add a `query-toggle` attribute to blocks that support it.
4. In the support file, enable the support on selected blocks or block variations.
5. Wire everything into `block-extensions/index.js` and rebuild.

### Follow-ups

After creating a block extension, users may:

- Refine the control’s UI with InspectorControls.
- Combine the extension with block bindings, variations, or query loop hooks.

Ask what behavior they want to unlock next and use the relevant block-editor skills.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/block-extension.js.tpl](assets/block-extension.js.tpl) | Extension entry wiring filters |
| Template | [assets/block-extension-control.js.tpl](assets/block-extension-control.js.tpl) | Minimal HOC control |
| Template | [assets/block-extension-attribute.js.tpl](assets/block-extension-attribute.js.tpl) | Block attribute registration |
| Template | [assets/block-extension-support.js.tpl](assets/block-extension-support.js.tpl) | Optional block support flag |
| Template | [assets/BlockEditorAssetsEnqueuer.php.tpl](assets/BlockEditorAssetsEnqueuer.php.tpl) | PHP hook to enqueue block-editor assets |
