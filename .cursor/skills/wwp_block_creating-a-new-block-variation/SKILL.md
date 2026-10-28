---
name: creating-a-new-block-variation
description: >-
  Creates a new Gutenberg block variation for an existing block, including
  editor-side JavaScript registration, file structure, and build integration.
  Use when the user wants a preset variation of an existing block.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new block variation

## Goal

Add a JS-registered block variation for an existing block in a plugin, including folder layout, `registerBlockVariation` code, webpack entry, and asset enqueue wiring.

## Rules

- **Mandatory inputs:** variation title; target plugin; block name the variation is based on (e.g. `core/query`, `my-plugin/card`).
- **Optional (variation object fields):**
  - `name` (string): unique machine-readable variation name.
    - If omitted, compute a slug from the title.
  - `description` (string): human-readable description.
  - `category` (string): category for search/filtering.
  - `keywords` (string[]): search keywords.
  - `icon` (string/object): icon for the variation.
  - `attributes` (object): attribute overrides.
  - `innerBlocks` (array[]): initial nested block configuration.
  - `example` (object): preview data for the inserter.
  - `scope` (`block` | `inserter` | `transform`): where the variation appears.
    - Defaults to `["block", "inserter"]`.
  - `isDefault` (bool): whether this is the default variation.
  - `isActive` (function|string[]): how to detect if a variation is active.
- **Special case – Query Loop variations (core/query):**
  - If the variation targets `core/query`, ask for:
    - Post type: which CPT the query should target.
    - Post template layout: list vs grid, and column count for grid.
    - Post template inner block/pattern: which block or pattern to use for each item.

## Workflow

### Step 1: File and folder structure

**Do:**

In the target plugin:

1. Ensure `src/block-editor` exists; create it if needed.
2. Ensure `src/block-editor/block-variations` exists; create it if needed.
3. Ensure `src/block-editor/block-variations/index.js` exists; create it if needed.
4. Under `src/block-editor/block-variations`, ensure a folder exists for the base block:
   - `src/block-editor/block-variations/<block-name>/`.
5. Inside that folder, create the variation file:
   - `src/block-editor/block-variations/<block-name>/<block-variation-slug>.js`.
6. Confirm the plugin has `webpack.config.js`:
   - If not, follow *setting-up-block-compilation* to create and wire it.

**Success criteria:** Variation file path exists and webpack config is present or *setting-up-block-compilation* was applied.

### Step 2: Variation registration code

**Do:**

In `src/block-editor/block-variations/<block-name>/<block-variation-slug>.js`, generate the registration code. Use this template as a starting point:

```js
/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerBlockVariation } from "@wordpress/blocks";
import { Icon, "<relevantIcon>" as icon } from "@wordpress/icons";

const VARIATION_NAME = "<fully-qualified/block-variation-name>"; // e.g. "my-plugin/post-carousel"
const BASE_BLOCK = "<fully-qualified/block-name>"; // e.g. "core/query"
const VARIATION_CLASSNAME = "<block-variation-classname>"; // e.g. "is-style-post-carousel"

/**
 * Registers block variation.
 */
registerBlockVariation(BASE_BLOCK, {
  name: VARIATION_NAME,
  title: __("<my-variation-title>", "<my-plugin-text-domain>"),
  description: __("<my-variation-description>", "<my-plugin-text-domain>"),
  icon: <Icon color="currentColor" icon={icon} />,
  scope: ["block", "inserter", "transform"],
  isDefault: false,
  isActive: (blockAttributes) => {
    return blockAttributes?.className?.includes(VARIATION_CLASSNAME);
  },
  attributes: {
    className: VARIATION_CLASSNAME,
  },
});
```

- Replace placeholders based on user input:
  - `VARIATION_NAME` as `namespace/slug`.
  - `BASE_BLOCK`.
  - `VARIATION_CLASSNAME` derived from the variation slug.
  - Titles, descriptions, and textdomain.
- For query loop variations:
  - Add `attributes` and/or `innerBlocks` for post type, layout, and inner template as needed.

**Success criteria:** Variation file registers with correct names, className strategy, and query-specific attributes when applicable.

### Step 3: Build integration

**Do:**

1. Check `webpack.config.js` in the plugin:
   - Ensure it has an entry mapping for `js/block-editor` to `src/block-editor/index.js`:

   ```js
   entry: {
     // ...
     "js/block-editor": path.resolve(__dirname, "src/block-editor/index.js"),
     // ...
   }
   ```

   - Add if missing.

2. In `src/block-editor/block-variations/index.js`:
   - If there’s no block variation section, add a comment header:

   ```js
   /**
    * Block variations
    *
    * This file is used to register block variations for the block editor.
    */
   ```

   - Add an import for the new variation file beneath this comment, preceded by a one-line comment explaining the variation:

   ```js
   // My custom query loop variation
   import "./<block-name>/<block-variation-slug>";
   ```

3. Ensure `src/block-editor/index.js` (or equivalent entry) imports the block-variations index if required by your structure.

4. Check for `includes/Hooks/BlockEditorAssetsEnqueuer.php`:
   - If absent, create it (retrieving the plugin namespace) and enqueue the `build/js/block-editor.js` and `build/css/block-editor.css` assets as per the template in your original rule.

**Success criteria:** Webpack builds `block-editor` bundle; variation is imported from index; assets are enqueued in PHP.

### Step 4: Document and ship

**Documentation:**

- In the plugin’s `README.md`:
  - Locate or create a “Block Variations” section.
  - Add a description of the new variation:
    - Base block it extends.
    - Variation name and class.
    - Intended use.

**Proposing a commit:**

- Once the variation builds and appears correctly in the editor:
  - Propose a commit summarizing:
    - New variation file and index imports.
    - Any asset enqueuer additions.
    - README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README documents the variation; commit covers JS, enqueue wiring, and README.

## Advanced Features

### Example – Query Loop variation

User intent:

> \"I want a query loop variation that shows a 3-column grid of posts.\"

Flow:

1. Target plugin and base block: `core/query`.
2. Decide variation slug: `post-grid-3-cols`.
3. Create `src/block-editor/block-variations/core-query/post-grid-3-cols.js` using the template:
   - Set `BASE_BLOCK` to `core/query`.
   - Configure `attributes` and/or `innerBlocks` to a 3-column layout.
4. Wire it into `src/block-editor/block-variations/index.js`.
5. Build the plugin and verify the variation appears under `core/query` in the inserter.

### Follow-ups

After adding a variation, users may:

- Create corresponding style variations.
- Associate block bindings or query loop filters with that variation.

Ask which enhancement they want next and use the relevant skills (style variations, bindings, query loop hooks).
