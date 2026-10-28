---
name: creating-a-block-category
description: >-
  Creates a custom Gutenberg block category for a plugin by wiring a hook that
  filters block_categories_all and adds a new category entry. Use when the user
  wants a dedicated category for their blocks in the inserter.
metadata:
  domain: wonderwp-block-editor
---

# Creating a Block Category

## Goal

Register a dedicated Gutenberg block category for a plugin so custom blocks appear under it in the inserter.

## Rules

- **Mandatory inputs:** target plugin where the block category will be registered; category slug (machine name); category title (human-readable label).
- When the hook class resides in `includes/Hooks` and the plugin’s autoload/manager is configured to discover hooks there, it will be loaded automatically; no extra registration is needed.

## Workflow

### Step 1: Create the hook class

The block category is registered via a hook in the plugin.

**Do:**

1. In the target plugin, use the *creating-a-new-hook* skill as a foundation:
   - Target the plugin and describe the purpose as “register a block category”.
2. Place the hook class in the plugin’s `includes/Hooks` directory.
3. Ensure the class:
   - Implements `RegistrableInterface` and `HasHookManagerInterface`.
   - Uses the `HasHookManager` trait.

**Success criteria:** A hook class exists under `includes/Hooks` wired for category registration.

### Step 2: Wire the category filter

**Do:**

In the hook class `register()` method, add a filter for the block categories:

```php
$this->addFilter('block_categories_all', [$this, 'addBlockCategory'], 10, 2);
```

Then implement the callback method:

```php
public function addBlockCategory(array $categories, $post): array
{
    $cat = [
        'slug'  => 'your-slug',
        'title' => __('Your Title', 'your-textdomain'),
        // 'icon' => 'your-icon', // Optional
    ];

    array_unshift($categories, $cat);

    return $categories;
}
```

Adapt:

- `your-slug` to the desired category slug.
- `Your Title` and `your-textdomain` to the actual label and plugin textdomain.
- Optionally set an `icon`.

**Success criteria:** `block_categories_all` is filtered and the callback returns the category array with your entry prepended.

### Step 3: Document and ship

**Documentation:**

- In the plugin’s `README.md`:
  - Add or update a “Hooks” (or “Blocks”) section.
  - Describe the new block category:
    - Slug.
    - Label.
    - Intended usage (e.g., housing custom blocks for the plugin).

**Proposing a commit:**

- Once the category appears in the editor and blocks can be assigned to it:
  - Propose a commit summarizing:
    - New hook class.
    - README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README documents the category; commit message covers hook class and README updates.

## Advanced Features

### Example – “My Plugin Blocks” category

User intent:

> \"Create a ‘My Plugin Blocks’ category for my blocks.\"

Flow:

1. Collect:
   - Slug: `my-plugin-blocks`.
   - Title: `My Plugin Blocks`.
2. Create a hook class in `includes/Hooks/MyPluginBlockCategory.php` using the template above.
3. Confirm the plugin is autoloading hooks from `includes/Hooks`.
4. Assign new blocks to this category via their `block.json` `category` field.
5. Document the hook and category in the plugin README.

### Follow-ups

After adding a category, users may:

- Update existing blocks to use the new category.
- Add variations and extensions that rely on category-based filtering or grouping.
