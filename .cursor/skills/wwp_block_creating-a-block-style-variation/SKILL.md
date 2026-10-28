---
name: creating-a-block-style-variation
description: >-
  Creates a new PHP-level block style variation for an existing block, by
  defining an AbstractBlockStyle subclass and wiring label and name. Use when
  the user wants an additional style variation option in the editor for a
  block.
metadata:
  domain: wonderwp-block-editor
---

# Creating a Block Style Variation for an Existing Block

## Goal

Add a PHP-level block style variation for an existing block via an `AbstractBlockStyle` subclass with label and name.

## Rules

- **Mandatory inputs:**
  - Block type to target: fully-qualified `namespace/blockname` (e.g. `core/paragraph`, `my-plugin/card`). If user omits `/`, ask them to specify in `namespace/blockname` format.
  - Style variation label: human-readable label shown in the editor.
  - Plugin where the style variation should be created.
- Once the class is in `includes/BlockStyles`, WonderWP’s discovery/manager mechanisms can pick it up (depending on project conventions). Ensure:
  - The plugin’s manager or autoload configuration includes `includes/BlockStyles` as a discovery root, if required by your setup.
- **Design-system variant → block style:** if the style should match an existing atom/molecule variant (tokens, generated CSS, remap carnet), do **not** hand-write the PHP class. Follow [`declare-block-style`](../../../wonderwp-design-system/skills/declare-block-style/SKILL.md) instead. This skill stays for styles that are **not** driven by the design system.

## Workflow

### Step 1: Prepare folder and namespace

**Do:**

1. In the target plugin, ensure `includes/BlockStyles` exists:
   - If not, create it.
2. Deduce the PHP namespace based on the plugin (e.g. `YourPluginNamespace\BlockStyles`).

**Success criteria:** `includes/BlockStyles` exists and namespace is identified.

### Step 2: Generate the block style class

**Do:**

1. Determine the class/file name:
   - Derive a slug from the style variation label.
   - Create a CamelCase class name from the slug (e.g. `FancyCardStyle`).
2. Create a new PHP file under `includes/BlockStyles`, using that class name.
3. Extend `AbstractBlockStyle` and implement the required methods.

Use this template:

```php
<?php

namespace YourPluginNamespace\BlockStyles;

use WonderWp\Component\BlockStyle\Definition\AbstractBlockStyle;

class YourBlockStyleVariation extends AbstractBlockStyle
{
    public function getKey(): string
    {
        return 'namespace/blockname'; // Replace with the actual block type
    }

    public function getArgs(): array
    {
        return [
            'label' => 'The variation label',          // Replace with editor label
            'name'  => 'the-variation-label-as-slug',  // Slugified version of the label
        ];
    }
}
```

Adapt:

- Namespace and class name.
- `getKey()` to the actual block type string.
- `label` to the variation label (optionally wrapped with `__()` and a textdomain).
- `name` to a slug version of the label.

**Success criteria:** Class file implements `getKey()` and `getArgs()` for the target block and variation slug.

### Step 3: Document and ship

**Documentation:**

- In the plugin’s `README.md`:
  - Add or update a “Block Styles” or “Editor Blocks” section.
  - Document:
    - The target block.
    - The new style variation name/label and slug.

**Proposing a commit:**

- Once the style variation appears in the editor:
  - Propose a commit summarizing:
    - New BlockStyle class.
    - Any README additions.
  - Provide the corresponding `git commit` command.

**Success criteria:** README lists target block and variation; commit covers class and README.

## Advanced Features

### Example – Outline style for a Card block

User intent:

> \"Add an ‘Outline’ style for my `my-plugin/card` block.\"

Flow:

1. Gather:
   - Block type: `my-plugin/card`.
   - Label: `Outline`.
   - Plugin namespace.
2. Create `includes/BlockStyles/OutlineCardStyle.php` (name adapted as needed).
3. Implement:

```php
public function getKey(): string
{
    return 'my-plugin/card';
}

public function getArgs(): array
{
    return [
        'label' => 'Outline',
        'name'  => 'outline',
    ];
}
```

4. Rebuild or clear caches if necessary and verify the style is available in the block editor.

### Follow-ups

After adding a style variation, users may:

- Provide CSS for the style in block or theme stylesheets.
- Combine this style variation with block variations or extensions for richer behavior.
- If the next style should come from a **design-system variant**, switch to [`declare-block-style`](../../../wonderwp-design-system/skills/declare-block-style/SKILL.md).

Ask which enhancement they want to pursue next and follow the related skills (block variations, extensions, theme styles, or DS block styles).
