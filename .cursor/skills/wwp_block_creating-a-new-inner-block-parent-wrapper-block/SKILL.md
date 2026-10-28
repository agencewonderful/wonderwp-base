---
name: creating-a-new-inner-block-parent-wrapper-block
description: >-
  Turns an existing block into an inner block parent wrapper, wiring it to host
  InnerBlocks with configurable allowed blocks and templates, and adding a save
  component. Use when the user wants a container block that holds other blocks.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new Inner Block Parent Wrapper Block

## Goal

Turn an existing scaffolded block into an InnerBlocks parent wrapper with optional allowed blocks/template and a `save.js` that persists inner content.

## Rules

- This skill assumes the block has already been generated (e.g. via *creating-a-new-block*). If not, use that skill first.
- **Mandatory inputs:**
  - Block type (fully qualified block name) to modify.
  - The plugin where the block is stored.

## Workflow

### Step 1: Retrieve the block

**Do:**

1. Confirm or ask for the block type if not provided.
2. In the plugin’s `src/blocks` folder, locate the block folder matching the type.
3. Open its `edit.js` file; this is where the editor UI is defined.

**Success criteria:** You are editing the correct `edit.js` for the target block.

### Step 2: Update imports

**Do:**

In `edit.js`:

1. Ensure imports from `@wordpress/block-editor` include:

```js
import { InnerBlocks, useInnerBlocksProps } from '@wordpress/block-editor';
```

2. If `useBlockProps` is already imported, keep it and extend the import list accordingly.

**Success criteria:** `InnerBlocks` and `useInnerBlocksProps` are imported from `@wordpress/block-editor`.

### Step 3: Use useInnerBlocksProps in Edit

**Do:**

In the block’s `Edit` function:

1. Prepare inner blocks props:

```js
const innerBlocksProps = useInnerBlocksProps({
  // allowedBlocks: [],
  // template: [],
});
```

2. Render inner blocks inside the block’s markup:

```jsx
return (
  <div {...useBlockProps()}>
    <div className="wrapper-container">
      <InnerBlocks {...innerBlocksProps} />
    </div>
  </div>
);
```

Adapt classes and structure to the block’s design.

**Success criteria:** Edit UI wraps `<InnerBlocks {...innerBlocksProps} />` inside the block structure.

### Step 4: Configure allowed blocks and template (optional)

**Do:**

Ask the user:

- Which child blocks should be allowed?
- Should there be a default template of child blocks?

If provided, update `useInnerBlocksProps`:

```js
const innerBlocksProps = useInnerBlocksProps({
  allowedBlocks: ['core/paragraph', 'core/image'],
  template: [['core/paragraph', { placeholder: 'Add text...' }]],
});
```

**Success criteria:** `allowedBlocks` / `template` match user requirements when specified.

### Step 5: Save component

Inner block parent wrapper blocks must save their inner content.

**Do:**

#### Create `save.js`

1. In the block’s folder, create `save.js` with:

```js
import { InnerBlocks } from '@wordpress/block-editor';

const Save = () => {
  return <InnerBlocks.Content />;
};

export default Save;
```

2. In `index.js`, import and wire `Save`:

```js
import Edit from './edit';
import Save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
  edit: Edit,
  save: Save,
});
```

Ensure existing registration is updated to include `save`.

**Success criteria:** `save.js` exports `<InnerBlocks.Content />` and `index.js` registers both `edit` and `save`.

### Step 6: Compile and verify

**Do:**

1. Run the plugin’s build command from the project root:

```bash
npm run build:<plugin-slug>
```

2. Verify in the editor:
   - The block acts as a container.
   - Inner blocks can be inserted, configured, and saved correctly.

**Success criteria:** Build succeeds; container behavior and inner block persistence work in the editor.

### Step 7: Document and ship

**Documentation:**

- Update the plugin’s README “Blocks” section (or create one) to:
  - Describe the block as an inner block parent wrapper.
  - Mention allowed child blocks and default template if any.

**Proposing a commit:**

- Once the behavior is correct:
  - Propose a commit summarizing:
    - Changes to `edit.js`, new `save.js`, and index wiring.
    - README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README reflects wrapper behavior; commit message covers edit/save/index and README.

## Advanced Features

### Example – Slider parent wrapper

User intent:

> \"Turn my Slider block into a parent wrapper that holds slide blocks.\" 

Flow:

1. Locate the Slider block in `src/blocks/slider`.
2. Update `edit.js` to:
   - Import `InnerBlocks` and `useInnerBlocksProps`.
   - Render `<InnerBlocks {...innerBlocksProps} />` inside the slider container.
3. Add `save.js` returning `<InnerBlocks.Content />` and wire it in `index.js`.
4. Build and verify in the editor that the Slider acts as a wrapper for inner slide blocks.

### Follow-ups

After creating a parent wrapper block, users might:

- Create dedicated child blocks optimized for that wrapper.
- Add style and behavior variations for different container layouts.

Ask which inner blocks or layouts they want next and apply relevant block editor skills.
