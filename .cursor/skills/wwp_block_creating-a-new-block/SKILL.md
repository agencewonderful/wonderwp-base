---
name: creating-a-new-block
description: >-
  Scaffolds a new Gutenberg block for a WonderWP plugin using
  @wordpress/create-block, ensures block compilation is configured, and wires
  the block into registration and build workflows. Use when the user wants to
  create a new block in a given plugin.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new block

## Goal

Scaffold a new Gutenberg block in a WonderWP plugin with `@wordpress/create-block`, ensure compilation and registration are wired, and prepare for follow-up block behaviors.

## Rules

- **Mandatory inputs:** block name (used for title/slug); target plugin where the block should live.
- **Optional / CLI flags:**
  - `--variant`: block variant as defined by the template (default `dynamic`).
  - `--no-plugin`: whether to scaffold only block files (default `true`).
  - `--target-dir <directory>`: folder where files will be scaffolded (defaults to slug from name).
  - `--namespace <value>`: internal namespace for the block name (should match plugin namespace).
  - `--title <value>`: block title (normally use the gathered name).
  - `--short-description <value>`: short description for the block and plugin.
  - `--category <name>`: block category name.
- Clarify any missing options, propose sensible defaults (dynamic variant, no-plugin true), and confirm with the user.
- **Important:** Compiled files (e.g. in `build/`) should not be versioned. Ensure `.gitignore` excludes them.
- **Editor UI:** Prefer a **canvas that mirrors the front** in `edit.js` (same structure/classes as `render.php` when practical). Put **form fields and block settings in the sidebar** with `InspectorControls`, usually via a dedicated module (e.g. `inspector-controls.js`) imported into `edit.js`. Full guidance is in **Step 4** below, including exceptions (canvas-first builders).

## Workflow

### Step 1: Ensure block compilation setup

**Do:**

1. Blocks should live under the plugin’s `src/blocks` folder:
   - If `src/blocks` does not exist, create it.
2. Ensure the plugin has block compilation configured:
   - If not, follow the *setting-up-block-compilation* skill to:
     - Create `webpack.config.js` in the plugin.
     - Add `start:<plugin>` and `build:<plugin>` scripts in the project’s root `package.json`.
     - Configure the plugin manager’s `path.blocks.build` and `blocks.manifest.path`.

**Success criteria:** `src/blocks` exists and the plugin has a working block build configuration (or you have applied *setting-up-block-compilation*).

### Step 2: Generate the block with @wordpress/create-block

**Do:**

1. From the plugin’s `src/blocks` folder, prepare the `npx` command:

   ```bash
   npx @wordpress/create-block@latest
   ```

2. Apply parameters:
   - Always include:
     - `--title` based on the block name.
     - `--namespace` matching the plugin.
   - Use defaults unless overridden:
     - `--no-plugin` defaults to `true` if not specified.
     - `--variant "dynamic"` if the user did not provide a variant.
   - Add any additional flags gathered from the user.
3. Execute the command and ensure the block files are generated under `src/blocks/<block-slug>`.

**Success criteria:** Block folder and files exist under `src/blocks/<block-slug>`.

### Step 3: Correct common mistakes in block.json

**Do:**

After generation, open the block’s `block.json` file and correct typical issues:

- `textdomain`:
  - Must match the plugin textdomain, not the block name.
- `category`:
  - If the plugin declares a custom block category (via a hook in `includes/Hooks`), ensure the block uses that category.
- `icon`:
  - If still the default smiley, suggest or set a more appropriate icon.

**Success criteria:** `block.json` reflects plugin textdomain, correct category, and a non-default icon where appropriate.

### Step 4: Structure `edit.js` — canvas preview vs Inspector

**Do:**

1. **Canvas (`edit.js`):** Treat the block’s visible area in the editor as a **preview of what visitors see**, not a settings form.
   - Reuse the same wrapper (via `useBlockProps`), inner markup, and CSS classes as `render.php` where practical so styles and semantics stay aligned.
   - Import shared front styles (`style.scss` / `style-index.css`) in `edit.js` when the preview should match the site.
2. **Sidebar:** Put **inputs that change attributes** (`TextControl`, `ToggleControl`, `SelectControl`, media/URL pickers, repeaters, etc.) inside **`InspectorControls`** — typically **`PanelBody`** sections — not in the canvas.
   - Extract them into a separate file (e.g. `inspector-controls.js`) and import that component from `edit.js` so `edit.js` stays focused on layout/preview.
3. **Preview logic:** When the preview depends on derived rows or validation (URLs, filtered lists, etc.), centralize the rules in a small helper (e.g. `preview-rows.js`) and document that it should stay consistent with `render.php` (or share a single source of truth on the server if you introduce a lightweight API later).
4. **Editor-only behavior:** Avoid breaking the mirror: use small affordances only when needed (e.g. `onClick` + `preventDefault` on preview links so clicks do not navigate away from the editor; optional `editor.scss` for cursor/`pointer-events` if justified). Do not replace the preview with a generic placeholder unless the block has nothing visual to show.
5. **Exceptions:** Some blocks are intentionally **canvas-first** (layout builders, drag-and-drop zones, WYSIWYG that *is* the content). In those cases, weighing UX over a strict mirror is fine — still keep inspector-only settings where they clearly belong (global options, toggles, design tokens).

**Success criteria:** Settings live in `InspectorControls` (ideally a dedicated module); the canvas resembles the front output; any intentional deviation is justified by the block type.

### Step 5: Compile the block

**Do:**

1. From the project root (not the plugin root), run the appropriate build command:

```bash
npm run build:<plugin-slug>
```

2. Confirm:
   - The plugin’s build directory (e.g. `build/blocks`) contains the compiled assets.
   - A `blocks-manifest.php` is generated if configured.

**Success criteria:** Build succeeds; compiled assets (and manifest if configured) are present; build artifacts remain gitignored.

### Step 6: Register the block

**Do:**

Block registration depends on the plugin’s manifest strategy:

- If `build/blocks/blocks-manifest.php` exists:
  - New blocks discovered via the manifest are registered automatically; no additional registration is needed.
- If there is no `blocks-manifest.php`:
  - A dedicated BlockType class is required:
    - Follow the *creating-a-new-block-type* skill to generate and register a `BlockType` under `includes/BlockTypes`.

**Success criteria:** Block is registered either via manifest discovery or via a `BlockType` as required by the plugin.

### Step 7: Route block behavior and follow-up work

**Do:**

Once the boilerplate is ready, ask the user what kind of block they’re building and route appropriately:

- **Query loop post card block**:
  - Follow the *creating-a-new-query-loop-post-card-block* skill.
- **Inner block parent wrapper**:
  - Follow the *creating-a-new-inner-block-parent-wrapper-block* skill.
- **Child of an inner block wrapper**:
  - Ask for the parent block type.
  - In the block’s `block.json`, ensure the `parent` attribute includes the parent block reference:

    ```json
    "parent": ["core/group"]
    ```

- **Simple block showing a post meta/custom field**:
  - Ask for the meta/custom field key.
  - Update `render.php` to output that value based on the post/context.

**Success criteria:** The user is directed to the correct follow-up skill or concrete `block.json` / `render.php` adjustments are identified.

## Advanced Features

### Example – Basic dynamic block in a plugin

User intent:

> \"Create a new dynamic card block in my plugin.\"

Flow:

1. Confirm:
   - Plugin and block name.
2. Ensure `src/blocks` exists and block compilation is set up.
3. Run:

```bash
cd path/to/plugin/src/blocks
npx @wordpress/create-block@latest --title="Card" --namespace="my-plugin" --variant="dynamic" --no-plugin
```

4. Correct `block.json` textdomain and category, adjust icon.
5. Structure the editor per **Step 4** (preview in `edit.js`; controls in `InspectorControls` / `inspector-controls.js`).
6. From project root, run `npm run build:my-plugin`.
7. If necessary, create a `BlockType` using the dedicated skill and document the new block in the plugin README.

### Follow-ups

After scaffolding a new block, typical follow-ups include:

- Turning it into a query loop post card block.
- Converting it into an inner block parent wrapper.
- Adding style or functional variations, bindings, or extensions using other block editor skills.

Ask the user which direction they want to pursue next and apply the appropriate skill.
