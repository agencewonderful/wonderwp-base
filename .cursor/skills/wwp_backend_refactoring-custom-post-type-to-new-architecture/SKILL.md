---
name: refactoring-custom-post-type-to-new-architecture
description: >-
  Refactors a plugin's custom post type from the old WonderWP architecture to
  the new PostTypes/custom-fields/service-based architecture, including CPT
  class migration, custom fields conversion, taxonomy recreation, manager and
  hook service updates, and documentation. Use when modernizing an existing
  WonderWP CPT.
metadata:
  domain: wonderwp-backend
---

# Refactoring Custom Post Type to New WonderWP Architecture

## Goal

Refactor a legacy WonderWP CPT (`includes/CPT`, manager config, hook registration, MetaDefinitions) to the modern layout (`includes/PostTypes`, `AbstractCptService`, dedicated CustomFields, auto-registration).

## Rules

- **`AbstractCptService` is abstract** and cannot be instantiated directly. There must be a concrete service class per plugin.
- Use *creating-a-new-custom-posttype*, *creating-custom-fields*, and *creating-a-new-taxonomy* where indicated below.

## Workflow

### Step 1: Analyze current CPT configuration

**Do:**

Inspect the existing implementation to understand the current state:

- **CPT class**:
  - Typically in `includes/CPT/YourCptCPT.php` extending `CustomPostType`.
- **Manager configuration**:
  - In `includes/YourManager.php`, look for CPT configuration in `register()` (e.g. `setConfig('cpt', ...)` and related options).
- **Hook service**:
  - Check `includes/Service/YourHookService.php` for CPT registration hooks and meta handling.
- **Meta definitions**:
  - Identify any `getMetaDefinitions()` logic related to the CPT’s custom fields.

**Success criteria:** You have located CPT class, manager config, hook service behavior, and meta definitions.

### Step 2: Extract CPT information

**Do:**

From the current implementation extract:

- CPT key (often via constants like `WWP_PLUGIN_NAME` or dedicated keys).
- Labels and associated textdomain.
- Arguments:
  - `public`, `hierarchical`, `supports`, `rewrite`, `taxonomies`, etc.
- Custom capabilities configuration, if present.
- Meta definitions that need to be converted into custom fields.

Keep this information handy for the refactor steps.

**Success criteria:** All args, labels, taxonomies, caps, and meta to migrate are written down.

### Step 3: Create the new PostType class

**Do:**

1. Follow the *creating-a-new-custom-posttype* skill:
   - Create a new class in `includes/PostTypes` extending `AbstractCustomPostType` and implementing the correct interfaces.
   - Ensure the key, labels, args, and capabilities mirror the old implementation as closely as possible.
2. Be especially careful about:
   - `supports` (editor, thumbnail, custom fields, etc.).
   - Taxonomy associations.
   - Rewrite rules (slugs, permalinks).
   - Capabilities:
     - Move capability definitions from manager configuration into the PostType class args.
     - Use `WwpAdminChangerService::$DEFAULTMODULECAP` for capabilities instead of relying on manager-level config.

**Success criteria:** New `PostType` class matches legacy behavior in key, labels, supports, taxonomies, rewrite, and capabilities.

### Step 4: Convert MetaDefinitions to custom fields

**Do:**

If the old CPT used `MetaDefinitions`:

1. Follow the *creating-custom-fields* skill:
   - Move meta definitions from the old CPT class into a dedicated CustomFields registry class.
2. For each meta field:
   - Create appropriate constants and field factory methods in the custom fields class.
   - Update any usages of meta constants in the codebase to reference the new custom fields class instead of the old CPT class.
3. Ensure the meta definitions are wired via the appropriate filters in the new custom fields class.

**Success criteria:** Custom fields registry owns meta definitions; references updated across the codebase.

### Step 5: Create or update associated taxonomy

**Do:**

If the old manager declared a taxonomy for the CPT:

1. Follow the *creating-a-new-taxonomy* skill to create the new taxonomy class in `includes/Taxonomies`.
2. Replicate the old taxonomy configuration as closely as possible:
   - `hierarchical`, `show_ui`, `show_in_rest`, `show_admin_column`, `query_var`, `public`, `publicly_queryable`, `show_in_nav_menus`, `show_in_admin_bar`, `exclude_from_search`, `rewrite`, etc.

**Success criteria:** Taxonomy lives under `includes/Taxonomies` with parity to legacy settings.

### Step 6: Create or update the CPT service

**Do:**

1. Check if a CPT service exists (e.g. `includes/Service/YourCptService.php`):
   - If it exists:
     - Change its parent from `CustomPostTypeService` to `AbstractCptService`.
     - Update imports:
       - Replace `use WonderWp\Component\CPT\CustomPostTypeService;` with `use WonderWp\Plugin\Core\Framework\AbstractPlugin\PostTypes\AbstractCptService;`.
     - Update constructor to remove any CPT-specific config parameter; the manager instance is sufficient.
   - If it does not exist:
     - Create a new `includes/Service/YourCptService.php` class extending `AbstractCptService`:

       ```php
       <?php

       namespace YourPluginNamespace\Service;

       use WonderWp\Plugin\Core\Framework\AbstractPlugin\PostTypes\AbstractCptService;

       class YourCptService extends AbstractCptService
       {
           // Add custom methods if needed (e.g., breadcrumb fixes).
       }
       ```

     - The class can be empty if no custom behavior is required.

**Success criteria:** Concrete `YourCptService extends AbstractCptService` is in place with correct imports.

### Step 7: Update the Manager

**Do:**

In `includes/YourManager.php`:

1. Remove old CPT configuration:
   - Delete any `setConfig('cpt', ...)` calls and related configuration arrays.
2. Clean up imports:
   - Remove imports referencing the old CPT class.
3. Update CPT service registration:
   - Ensure the manager registers the concrete `YourCptService` instead of `AbstractCptService`.
   - Add a `use` statement for the new CPT service class.
4. For theme overrides:
   - If this is a theme-level override of a plugin manager, ensure the `register()` method includes:

     ```php
     $this->setConfig('path.child.root', plugin_dir_path(dirname(__FILE__)));
     ```

**Success criteria:** Manager has no legacy `cpt` config; concrete CPT service is registered; theme override sets `path.child.root` when applicable.

### Step 8: Update the Hook service

**Do:**

In `includes/Service/YourHookService.php`:

1. Remove CPT registration hooks:
   - E.g. `addAction('init', [$this, 'createCustomPostType'])`.
2. Remove MetaDefinitions-related hooks:
   - E.g. `admin_init` and `save_post` hooks that manage old meta fields.
3. Remove references to the old CPT service for concerns that have been refactored (sitemap, breadcrumbs, etc.) if they are now handled elsewhere.
4. Delete the old `createCustomPostType()` method.
5. Clean up unused imports.

**Success criteria:** Hook service no longer registers CPT or old meta flow; imports are clean.

### Step 9: Remove old files

**Do:**

1. Delete the old CPT class file:
   - `includes/CPT/YourCptCPT.php`.
2. If `includes/CPT/` becomes empty, remove the directory.

**Success criteria:** Legacy CPT file and empty `CPT` folder are gone.

### Step 10: Update documentation

**Do:**

1. Create or update the plugin’s `README.md`:
   - Add a “Custom Post Type” section describing the new implementation.
   - Include key configuration details:
     - Key, slug, associated taxonomies, supports, capabilities, etc.

**Success criteria:** README reflects the new architecture and CPT details.

### Step 11: Testing checklist

**Do:**

After refactoring:

1. Activate the plugin and confirm the CPT registers without errors.
2. Verify in the WordPress admin:
   - The CPT appears in the menu.
   - New/edit screens behave as expected.
3. Test custom fields:
   - Ensure fields created from meta definitions are present and functional.
4. Exercise controllers and services relying on the CPT to catch regressions.
5. Check PHP logs for errors or deprecations.

**Success criteria:** Admin, fields, and dependent code work; no new PHP errors.

## Advanced Features

### Example – Migrating Info CPT

User intent:

> \"I want to migrate my Info CPT to the new WonderWP architecture.\"

Flow:

1. Analyze `InfoCPT` class, manager config, and hook service meta definitions.
2. Create `InfoPostType` under `includes/PostTypes` using the new pattern.
3. Move Info meta definitions into a `InfoCustomFields` class under `includes/CustomFields`.
4. If a taxonomy existed, recreate it using the new taxonomy skill.
5. Create or update `InfoCptService` extending `AbstractCptService` and update manager registration.
6. Remove the old `InfoCPT` class and clean `includes/CPT`.
7. Update README and verify behavior in the admin.

### Reference – Old architecture

- CPT defined in `includes/CPT/YourCptCPT.php` extending `CustomPostType`.
- Manual registration via `init` hook in the hook service.
- Configuration embedded in the manager’s `register()` method.
- `MetaDefinitions` logic tied to the CPT service.

### Reference – New architecture

- CPT defined in `includes/PostTypes/YourPostType.php` extending `AbstractCustomPostType`.
- Automatic CPT registration by virtue of living in `includes/PostTypes`.
- No manual registration in the manager.
- Custom fields handled via dedicated CustomFields classes.
- CPT service extends `AbstractCptService` and is always a concrete class.

### Benefits

- Automatic, convention-based registration; less boilerplate.
- Clear separation between CPT definition and custom fields.
- Cleaner `includes/PostTypes` folder organizing CPTs.
- Reduced complexity in managers and hook services.
- Better alignment with the WonderWP framework and future evolution.

### Follow-ups

After completing a CPT refactor, users often:

- Add or refactor associated taxonomies and custom fields.
- Introduce new blocks or frontend components targeting the refactored CPT.
- Perform similar refactors for other legacy CPTs.

Ask which CPT or component they want to address next and apply this process again.
