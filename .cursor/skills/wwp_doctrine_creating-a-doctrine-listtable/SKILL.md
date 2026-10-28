---
name: creating-a-doctrine-listtable
description: >-
  Creates a WonderWP DoctrineListTable for admin CRUD listing: columns, status display,
  and column_* renderers. Use when adding or customizing the backend list view for a Doctrine entity.
metadata:
  domain: wonderwp-doctrine
---

# Creating a Doctrine ListTable

## Goal

Produce `includes/ListTable/{Entity}ListTable.php` extending `DoctrineListTable` for the WordPress admin entity list.

## Rules

- **Prerequisites:** entity (**creating-a-doctrine-entity**); Manager wiring with `LIST_TABLE_SERVICE_NAME` (**wiring-doctrine-manager-and-services**).
- **Template:** [assets/EntityListTable.php.template](./assets/EntityListTable.php.template).
- **Placeholders:** `{Namespace}`, `{Entity}`, `{PluginConst}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).
- Hook service must call `$this->registerDoctrinePluginHooks()` (via `DoctrineHookServiceTrait`) so the list appears in admin.
- Customize columns in `get_columns()`; add `column_{name}($item)` methods for non-scalar display.
- Status/enabled columns often use parent helpers or custom renderers — align with entity design.

## Workflow

### Step 1: Define list requirements

**Do:**

1. List columns to show, hide, or add.
2. Identify sortable columns if needed (parent may provide defaults from entity attributes).
3. Define status/enabled display rules.

**Success criteria:** column spec matches UX/issue acceptance criteria.

### Step 2: Create ListTable class

**Do:**

1. Ensure `includes/ListTable/` exists.
2. Copy [assets/EntityListTable.php.template](./assets/EntityListTable.php.template) to `{Entity}ListTable.php`.
3. Replace placeholders.
4. Implement `get_columns()` adjustments and `column_*` renderers.

**Success criteria:** class extends `DoctrineListTable`.

### Step 3: Register service

**Do:**

1. In `{Plugin}Manager.php`, register:

```php
$this->addService(ServiceInterface::LIST_TABLE_SERVICE_NAME, function () {
    return new {Entity}ListTable();
});
```

**Success criteria:** admin menu list uses this ListTable.

### Step 4: Verify admin integration

**Do:**

1. Confirm HookService uses `DoctrineHookServiceTrait` and `registerDoctrinePluginHooks()`.
2. Activate plugin; open admin list page; verify columns and row actions.

**Success criteria:** CRUD list renders with expected columns.

## Reference

Grep `TrackerListTable` in the consumer project for column renderer patterns.

## Anti-patterns

- Do not build a manual `WP_List_Table` from scratch — extend `DoctrineListTable`.
- Do not forget i18n on custom column headers (`.trad` keys).
