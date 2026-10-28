---
name: setting-up-a-plugin-theme-override
description: >-
  Sets up a WonderWP plugin override inside the current theme, including
  activator configuration, discovery path configuration, override generation,
  and activation. Use when the user wants to customize a plugin's behavior
  from the theme via an override manager.
metadata:
  domain: wonderwp-backend
---

# Setting up a plugin override inside the current theme

## Goal

Configure a WonderWP plugin’s activator and manager for theme overrides, regenerate the child override tree, and activate the child manager in project config.

## Rules

- Gather: **target plugin** to override; **target theme** (default active theme—often `wwp_child_theme`; confirm if ambiguous).
- Confirm with the user before running commands that change plugin activation.

## Workflow

### Step 1: Locate the plugin activator service

**Do:**

1. In the plugin, look under `includes/Service` for the activator service class (e.g. `MyPluginActivator.php`).
2. If the file cannot be found:
   - Warn the user that the plugin activator service is missing, as it is required for override setup.
3. If found, proceed with compatibility checks.

**Success criteria:** Activator class path is known or user is blocked with a clear warning.

### Step 2: Add override capabilities to the activator

**Do:**

1. Ensure the activator class extends one of:
   - `WonderWp\Plugin\Core\Framework\AbstractPlugin\AbstractDoctrinePluginActivator`, or
   - `WonderWp\Plugin\Core\Framework\AbstractPlugin\AbstractPluginActivator`.
2. If it does not extend one of these:
   - Warn the user that it must extend the appropriate base activator class so that `setupOverride()` is available.
3. Check that the activator’s `activate()` method calls `setupOverride()`:
   - If not, add a call from [assets/setupOverride.php.tpl](assets/setupOverride.php.tpl), adapted to the target plugin.

Adjust:

- Namespaces for the plugin and theme.
- Manager class names.
- `override_dir` for the plugin slug and theme path.
- The manager constant name.

**Success criteria:** Activator extends the correct base and calls `setupOverride()` with project-specific values.

### Step 3: Add discovery path capabilities to the plugin manager

**Do:**

1. Locate the plugin manager in `includes/Service` (e.g. `MyPluginManager.php`).
2. If it cannot be found, warn the user that the plugin manager is missing.
3. In the manager `register()` method, check for a `discoveryPathsRoots` configuration from [assets/discoveryPathsRoots.php.tpl](assets/discoveryPathsRoots.php.tpl).

If such configuration is missing, add it using appropriate keys for the plugin and its child override.

**Success criteria:** Manager sets `discoveryPathsRoots` when `path.child.root` is non-empty.

### Step 4: Create the plugin override (deactivate / autoload / activate)

**Do:**

After setting up activator and manager configuration, generate the override by:

1. Deactivating and reactivating the plugin with WP-CLI:

```bash
wp plugin deactivate <plugin-slug>
composer dump-autoload
wp plugin activate <plugin-slug>
```

2. Confirm with the user before running commands that change plugin activation.

**Success criteria:** Plugin reactivation completes after autoload refresh.

### Step 5: Verify override location and manager file

**Do:**

#### Verify override location

1. Check the theme for the override directory:
   - Typically under `web/app/themes/wwp_child_theme/plugins/`.
   - Confirm a subfolder exists for the plugin (e.g. `web/app/themes/wwp_child_theme/plugins/wwp-actu`).
2. If the override folder or files do not exist, warn the user that the override did not work.

#### Verify override manager file

1. In the override plugin directory, under `includes`, locate the override manager file (e.g. `ActuThemeManager.php`).
2. If it does not exist, warn the user that the override did not work.
3. If it exists, confirm:
   - The namespace and class name are correct.
   - The `register()` method sets the child root path:

     ```php
     $this->setConfig('path.child.root', plugin_dir_path(dirname(__FILE__)));
     ```

   - If missing, add this before the `parent::register()` call.

**Success criteria:** Override folder and theme manager exist; `path.child.root` is set in child manager.

### Step 6: Activate the override in configuration

**Do:**

1. Open the project’s configuration file, typically `/config/application.php` from the project root.
2. Look for a commented line that assigns the child manager to a PHP constant.
3. If found and commented out:
   - Uncomment it to activate the child manager and thus the plugin override.

Confirm that:

- The override manager is being used.
- Plugin behavior reflects the override.

**Success criteria:** Child manager constant is active in `application.php` (or equivalent) and behavior matches override.

### Step 7: Document and ship

**Documentation:**

- Document the override setup in a project-level or theme README.
- Include:
  - Which plugin is overridden.
  - Where the override code lives in the theme.
  - Any special configuration in `application.php`.

**Proposing a commit:**

- Once the override is functioning correctly:
  - Propose a commit summarizing:
    - Activator changes.
    - Manager configuration updates.
    - Override manager additions.
    - Any configuration file edits.
  - Provide a clear commit message and matching `git commit` command.

**Success criteria:** Docs describe override location and config; commit is proposed.

## Advanced Features

### Example – Overriding the Actu plugin

User intent:

> \"I want to override the Actu plugin from my child theme.\"

Flow:

1. Locate `ActuActivator` and ensure it extends `AbstractPluginActivator` and calls `setupOverride()` with Actu-specific settings.
2. Ensure `ActuManager` has `discoveryPathsRoots` configured.
3. Deactivate and reactivate the `wwp-actu` plugin and regenerate autoload files.
4. Check that `web/app/themes/wwp_child_theme/plugins/wwp-actu/includes/ActuThemeManager.php` exists and sets `path.child.root`.
5. Activate the child manager in `config/application.php` by uncommenting the relevant line.

### Follow-ups

After the override is in place, users may:

- Customize plugin behavior in the override manager and related classes.
- Add theme-specific services, hooks, or templates for the overridden plugin.

Ask what aspects they want to customize and then follow the relevant WonderWP backend or block editor skills.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/setupOverride.php.tpl](assets/setupOverride.php.tpl) | Activator `setupOverride()` call |
| Template | [assets/discoveryPathsRoots.php.tpl](assets/discoveryPathsRoots.php.tpl) | Manager discovery paths config |
