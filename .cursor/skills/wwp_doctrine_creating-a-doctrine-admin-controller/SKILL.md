---
name: creating-a-doctrine-admin-controller
description: >-
  Creates or extends AbstractPluginDoctrineBackendController for custom admin routes,
  detail pages, and actions on a Doctrine entity. Use when the default CRUD flow is not
  enough (e.g. custom detail page, bulk actions).
metadata:
  domain: wonderwp-doctrine
---

# Creating a Doctrine admin controller

## Goal

Produce `includes/Controller/{Entity}AdminController.php` extending `AbstractPluginDoctrineBackendController` with custom admin routes when required.

## Rules

- **Prerequisites:** entity, ListTable, Manager admin controller registration.
- **Template:** [assets/EntityAdminController.php.template](./assets/EntityAdminController.php.template).
- **Placeholders:** `{Namespace}`, `{Entity}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).
- Default CRUD is provided by the parent — only add methods for **custom** pages (detail view, exports, modal actions).
- Register on Manager:

```php
$this->addController(AbstractManager::ADMIN_CONTROLLER_TYPE, function () {
    return new {Entity}AdminController($this);
});
```

- Resolve repository and forms via `$this->manager->getService(...)` — never bypass the DI container.

## Workflow

### Step 1: Clarify admin UX

**Do:**

1. Read issue acceptance criteria (e.g. custom detail page with related data).
2. List routes/actions beyond default list/add/edit/delete.
3. Decide tab structure if multiple admin sections (`getTabs()`).

**Success criteria:** custom vs default CRUD scope is explicit.

### Step 2: Create controller class

**Do:**

1. Ensure `includes/Controller/` exists.
2. Copy [assets/EntityAdminController.php.template](./assets/EntityAdminController.php.template) to `{Entity}AdminController.php`.
3. Replace placeholders.
4. Implement action methods (`detailAction`, etc.) following WonderWP admin controller conventions.

**Success criteria:** class extends `AbstractPluginDoctrineBackendController`.

### Step 3: Wire routes

**Do:**

1. Override `getDefaultRoute()` or register tab routes if needed.
2. Link from ListTable row actions if custom detail URL is required (may involve ListTable override or hook).

**Success criteria:** custom admin URL loads the new action.

### Step 4: Test

**Do:**

1. Log in as admin user with plugin capability.
2. Exercise list → detail → actions flow.

**Success criteria:** matches acceptance criteria.

## Reference

`TrackerAdminController` in `wwp-tracker` is minimal; grep other Doctrine plugins in the monorepo for richer examples.

## Anti-patterns

- Do not duplicate ListTable logic in the controller.
- Do not instantiate repositories with `new` — use Manager services.
