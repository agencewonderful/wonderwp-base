---
name: creating-a-new-taxonomy
description: >-
  Creates a new WonderWP custom taxonomy class, including key, labels, args, and
  association to one or more post types, and wires it into the appropriate plugin
  folder. Use when the user wants to add a custom taxonomy to a WonderWP plugin
  or mentions creating a new taxonomy.
metadata:
  domain: wonderwp-backend
---

# Creating a Custom Taxonomy

## Goal

Create a WonderWP taxonomy class (`AbstractTaxonomy`) in `includes/Taxonomies` with key, labels, args, and post-type associations for automatic registration.

## Rules

- When the user asks to create a custom taxonomy, gather the following.
- **Mandatory:** taxonomy name; taxonomy key; singular and plural labels; post type(s) the taxonomy should be associated with; plugin in which the taxonomy should be stored.
- If only the taxonomy name is provided, you may extrapolate the key and labels from the name and ask the user to confirm.
- **Optional:** description; hierarchical status; visibility (`show_ui`, REST, admin column, nav menus, admin bar); query behavior (`query_var`, `publicly_queryable`, `exclude_from_search`); URL rewrite settings.
- If options are not provided, offer the default argument set and ask the user to confirm:

```php
[
    'hierarchical' => false,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => false,
    'query_var' => false,
    'public' => false,
    'publicly_queryable' => false,
    'show_in_nav_menus' => true,
    'show_in_admin_bar' => false,
    'exclude_from_search' => true,
    'rewrite' => false
]
```

- Maintain a feedback list of what has been collected versus what is missing.
- The taxonomy will be automatically registered when the plugin is activated, as long as the class resides in `includes/Taxonomies`.

## Workflow

### Step 1: Generate the Taxonomy class

**Do:**

1. Determine the target plugin folder.
2. Inside that plugin, check for an `includes/Taxonomies` folder:
   - If it doesn’t exist, create it.
3. Create a Taxonomy class in `includes/Taxonomies`:
   - Class name: camel-case version of the taxonomy name with a `Taxonomy` suffix.
   - Extend `AbstractTaxonomy`.
   - Implement `HasCustomTypeDefinitionsInterface` and `HasLabelsInterface`.
4. Locate the correct PHP namespace from the plugin folder and apply it.
5. Implement the class using this structure as a starting point:

```php
<?php

namespace MyPluginNamespace\Taxonomies;

use MyPluginNamespace\PostTypes\AssociatedPostType;
use WonderWp\Component\Taxonomy\Definition\AbstractTaxonomy;
use WonderWp\Component\Taxonomy\Traits\HasCustomTypeDefinitions;
use WonderWp\Component\Taxonomy\Traits\HasCustomTypeDefinitionsInterface;
use WonderWp\Component\Taxonomy\Traits\HasLabels;
use WonderWp\Component\Taxonomy\Traits\HasLabelsInterface;

class MyTaxonomy extends AbstractTaxonomy implements HasCustomTypeDefinitionsInterface, HasLabelsInterface
{
    use HasCustomTypeDefinitions;
    use HasLabels;

    public static function provideKey(): string
    {
        return 'my-taxonomy-key';
    }

    public static function provideObjectTypes(): array
    {
        return ['associated-post-type'];
    }

    public static function provideArgs(): array
    {
        return [
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => false,
            'query_var' => false,
            'labels' => static::provideLabels(),
            'public' => false,
            'publicly_queryable' => false,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => false,
            'exclude_from_search' => true,
            'rewrite' => false
        ];
    }

    public static function provideLabels(): array
    {
        static::$labels = [
            'name' => __('MyTaxonomyPlural', MY_TEXTDOMAIN),
            'singular_name' => __('MyTaxonomySingular', MY_TEXTDOMAIN),
            'search_items' => __('Search MyTaxonomyPlural', MY_TEXTDOMAIN),
            'all_items' => __('All MyTaxonomyPlural', MY_TEXTDOMAIN),
            'edit_item' => __('Edit MyTaxonomySingular', MY_TEXTDOMAIN),
            'update_item' => __('Update MyTaxonomySingular', MY_TEXTDOMAIN),
            'add_new_item' => __('Add new MyTaxonomySingular', MY_TEXTDOMAIN),
            'new_item_name' => __('New MyTaxonomySingular name', MY_TEXTDOMAIN),
            'menu_name' => __('MyTaxonomyPlural', MY_TEXTDOMAIN),
        ];

        return static::getLabels();
    }
}
```

- Set `provideKey()` to the user-provided key.
- Set `provideObjectTypes()` based on the associated post type(s).
- Adjust `provideArgs()` from user-provided visibility and query options (or defaults).
- Fill `provideLabels()` with the correct labels and textdomain.

**Success criteria:** Class file exists with correct key, object types, args, and labels.

### Step 2: Document and ship

**Documentation:**

- Look for a `README.md` file in the plugin.
- If one exists, look for a “Taxonomies” section:
  - If present, add a line describing the new taxonomy and the post types it is attached to.
  - If absent, create a “Taxonomies” section and document the new taxonomy.

**Proposing a commit:**

- Once the taxonomy class and docs are ready, propose a commit to the user.
- Include a clear commit message summarizing the new taxonomy and the corresponding `git commit` command.

**Success criteria:** README lists taxonomy and attached post types; commit proposed.

## Advanced Features

### Example – Basic taxonomy for a CPT

User intent:

> \"I need a Category taxonomy for my Event custom post type.\"

Flow:

1. Collect:
   - Name: `Event Categories`.
   - Key: e.g. `event-category`.
   - Singular: `Event Category`, plural: `Event Categories`.
   - Associated post type: `event`.
2. Generate `includes/Taxonomies/EventCategoryTaxonomy.php`.
3. Set `provideKey()` to `event-category` and `provideObjectTypes()` to `['event']`.
4. Use default args or adjust based on visibility requirements.
5. Update plugin README “Taxonomies” section to describe the Event Categories taxonomy.

### Follow-ups

After creating a taxonomy, the user often wants to:

- Create custom fields for that taxonomy.
- Add blocks or filters that leverage taxonomy terms in the frontend.

Ask how they would like to proceed and then follow the associated WonderWP skills.
