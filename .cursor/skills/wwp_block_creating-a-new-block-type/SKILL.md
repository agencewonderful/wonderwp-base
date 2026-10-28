---
name: creating-a-new-block-type
description: >-
  Creates a new WonderWP BlockType class to register a Gutenberg block at the
  PHP level, wiring it to an existing block slug. Use when a generated block
  needs a BlockType for registration instead of or in addition to a manifest.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new BlockType

## Goal

Create a WonderWP `BlockType` class that registers an existing Gutenberg block at the PHP level when a manifest-based setup is not used.

## Rules

- **Mandatory inputs:** the block slug the BlockType will be associated with (e.g. `my-block-slug`); the plugin where the BlockType should be stored.
- BlockTypes are typically created immediately after scaffolding a block with *creating-a-new-block* when a manifest is not in use.

## Workflow

### Step 1: Prepare the folder

**Do:**

1. Determine the plugin folder.
2. Check for an `includes/BlockTypes` folder:
   - If it does not exist, create it.

**Success criteria:** `includes/BlockTypes` exists for the target plugin.

### Step 2: Generate the BlockType class

**Do:**

1. Create a class in `includes/BlockTypes` with a CamelCase name based on the block slug plus `BlockType` suffix (e.g. `MyCardBlockType`).
2. Locate and use the correct PHP namespace for the plugin.
3. Extend and implement the correct WonderWP interfaces:
   - Extend `AbstractBlockType`.
   - Implement `HasCustomTypeDefinitionsInterface`.

Use this template as a starting point:

```php
<?php

namespace MyPluginNamespace\BlockTypes;

use WonderWp\Component\BlockType\Definition\AbstractBlockType;
use WonderWp\Component\BlockType\Traits\HasCustomTypeDefinitions;
use WonderWp\Component\BlockType\Traits\HasCustomTypeDefinitionsInterface;

class MyBlockType extends AbstractBlockType implements HasCustomTypeDefinitionsInterface
{
    use HasCustomTypeDefinitions;

    public static function provideKey(): string
    {
        return 'my-block-slug';
    }

    public static function provideArgs(): array
    {
        return [];
    }
}
```

Adapt:

- Namespace and class name to the plugin and block.
- `provideKey()` to the real block slug.

For dynamic blocks, there is generally no need to add extra args in `provideArgs()`; the configuration is drawn from `block.json` and rendering is handled by `render.php`.

**Success criteria:** The class file exists with correct namespace, `provideKey()` matching the block slug, and `provideArgs()` as needed for dynamic blocks.

### Step 3: Document and ship

**Documentation:**

- Look for a `README.md` in the plugin.
- If one exists:
  - Locate or create an “Editor Blocks” section.
  - Add a line describing the new BlockType and its associated block.
  - Optionally mention how it fits into the registration/manifest strategy.

**Proposing a commit:**

- Once the BlockType class is in place and the block works after a build:
  - Propose a commit summarizing:
    - The new BlockType class.
    - Any README updates.
  - Provide the matching `git commit` command.

**Success criteria:** README describes the BlockType if present; commit message covers class and README changes.

## Advanced Features

### Example – BlockType for a dynamic card block

User intent:

> \"I generated a Card block but my plugin doesn’t use blocks-manifest; I need a BlockType.\"

Flow:

1. Identify:
   - Block slug: `my-card`.
   - Plugin and namespace.
2. Create `includes/BlockTypes/MyCardBlockType.php` using the template above.
3. Set `provideKey()` to `my-card`.
4. Rebuild the plugin and confirm the block is registered and available in the editor.
5. Document the BlockType under “Editor Blocks” in the README.

### Follow-ups

After the BlockType exists, users might:

- Add custom PHP-level behavior in `provideArgs()` if block metadata needs to be overridden or augmented.
- Combine BlockTypes with style variations, block bindings, or query loop behaviors using other block editor skills.
