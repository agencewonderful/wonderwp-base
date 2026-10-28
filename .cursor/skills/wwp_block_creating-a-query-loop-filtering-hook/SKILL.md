---
name: creating-a-query-loop-filtering-hook
description: >-
  Creates a WonderWP query loop filtering hook that filters query loops both in
  the editor and on the frontend based on a query identifier, using the
  FilteredQueryLoopByQueryIdentifier trait. Use when the user wants to
  customize query loop results for a specific identifier.
metadata:
  domain: wonderwp-block-editor
---

# Creating a Query Loop Filter Hook

## Goal

Set up a hook that filters Query Loop blocks in both the block editor and on the frontend, based on a specific `queryUniqueIdentifier`.

## Rules

- **Mandatory inputs:** plugin folder where the hook should live; hook purpose (e.g. “filter formation campus carousel”, “filter related posts”); `QUERY_UNIQUE_IDENTIFIER` value to target (e.g. `campus-in-context`, `related-posts`); post types that should be filtered in the REST API (e.g. `['my-post-type']`).
- **Optional:** any specific filtering rules or conditions; additional dependencies or services the hook should use.

## Workflow

### Step 1: Prepare the hook class

**Do:**

1. In the plugin, ensure `includes/Hooks` exists; create if needed.
2. Choose a CamelCase class name based on the purpose, typically ending with `QueryLoopHook` (e.g. `FilterFormationCampusCarouselQueryLoopHook`).
3. Use the appropriate namespace for the plugin (e.g. `MyPluginNamespace\Hooks`).
4. Copy [assets/QueryLoopHook.php.tpl](assets/QueryLoopHook.php.tpl) and adapt:
   - `[PluginNamespace]` and `[HookName]`.
   - `TARGETED_QUERY_IDENTIFIER`.
   - Default `postTypesToFilter`.

**Success criteria:** Hook class implements interfaces/traits, defines `TARGETED_QUERY_IDENTIFIER`, and registers REST + block filtering in `register()`.

### Step 2: Editor filtering (REST)

**Do:**

In `filterRestQueryVars()`:

- Use `getPostIdFromRestRequestReferer()` when appropriate to determine the current post in the editor.
- Check post type and other criteria before modifying `$args`.
- Return modified `$args` to restrict what appears in editor previews.

Example pattern:

```php
public function filterRestQueryVars(array $args, \WP_REST_Request $request): array
{
    $postId = $this->getPostIdFromRestRequestReferer($request);
    if (!$postId /* or wrong post type */) {
        return $args;
    }

    // Apply filtering based on $postId and args
    return $args;
}
```

**Success criteria:** REST branch narrows query vars appropriately for the editor context.

### Step 3: Frontend filtering

**Do:**

In `filterBlockQueryVars()`:

- Check context (e.g. `is_singular()` with a target CPT).
- Use global `get_post()` where appropriate.
- Modify `$query` to restrict posts returned by the loop.

Example pattern:

```php
public function filterBlockQueryVars(array $query, \WP_Block $block): array
{
    if (/* context does not match */) {
        return $query;
    }

    // Apply filtering based on current post context
    return $query;
}
```

**Success criteria:** Frontend branch narrows `$query` for matching singular/archive context.

### Step 4: Example implementation

**Do:**

Reference: `FilterFormationCampusCarouselQueryLoopHook` with identifier `campus-in-context` and `postTypesToFilter` `['esma-campus']`.

**Success criteria:** Reference pattern is understood for naming, identifier constant, and `postTypesToFilter` hook.

### Step 5: Usage with Query Loop blocks

**Do:**

For the hook to apply, Query Loop blocks must use the matching identifier:

```json
{
  "queryUniqueIdentifier": "campus-in-context"
}
```

Ensure the block’s attributes or configuration set `queryUniqueIdentifier` to `TARGETED_QUERY_IDENTIFIER`.

**Success criteria:** Block/query configuration uses the same identifier string as the hook constant.

### Step 6: Registration

**Do:**

- As long as the hook class lives in `includes/Hooks` and your plugin’s autoload/manager discovers hooks from there, no extra registration is needed.

**Success criteria:** Hook is autoloaded/discovered like other plugin hooks.

### Step 7: Document and ship

**Documentation:**

- In the plugin README:
  - Document:
    - The hook name and purpose.
    - The `TARGETED_QUERY_IDENTIFIER` it expects.
    - Which post types it filters.

**Proposing a commit:**

- Once the hook behaves as expected:
  - Propose a commit summarizing:
    - New query loop filtering hook.
    - Any config changes.
  - Provide the matching `git commit` command.

**Success criteria:** README documents hook, identifier, and post types; commit message matches.

## Advanced Features

### Example – Filter campus in context for formations

User intent:

> \"Filter the campus list for a formation-specific query loop in both editor and frontend.\" 

Flow:

1. Use identifier `campus-in-context` and target `esma-campus` in `postTypesToFilter`.
2. Implement `filterRestQueryVars()` to restrict REST responses when editing a Formation post.
3. Implement `filterBlockQueryVars()` to restrict frontend queries for Formation single pages.
4. Ensure Query Loop blocks set `"queryUniqueIdentifier": "campus-in-context"`.

### Follow-ups

After setting up a query loop filtering hook, users may:

- Add additional hooks for other identifiers.
- Combine with block variations or extensions that preset the query identifier.

Ask which query-based behavior they want to address next and apply this pattern accordingly.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/QueryLoopHook.php.tpl](assets/QueryLoopHook.php.tpl) | Query loop filtering hook skeleton |
