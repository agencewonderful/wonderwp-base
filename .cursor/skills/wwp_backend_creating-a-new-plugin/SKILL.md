---
name: creating-a-new-plugin
description: >-
  Creates and registers a new WonderWP backend plugin using the wp wonderwp:generate-plugin
  command, including required parameters, optional metadata, autoload configuration, activation,
  and basic documentation. Use when the user wants to create a new WordPressplugin from scratch,
  mentions generating a plugin, or asks how to scaffold a new backend plugin.
metadata:
  domain: wonderwp-backend
---

# Creating a new WonderWP backend plugin

## Goal

Scaffold a new WonderWP backend plugin with `wp wonderwp:generate-plugin`, wire Composer PSR-4 autoload, activate the plugin, and prepare minimal docs plus git tracking.

## Rules

The skill uses the `wp wonderwp:generate-plugin` command to scaffold a new plugin.

Basic command structure:

```bash
wp wonderwp:generate-plugin --name="myPluginName" --namespace="WonderWp\Plugin\MyPluginNameSpace" [optional parameters]
```

**Required parameters:**

- `--name`: The name of the plugin as displayed in the WordPress Admin Plugins list.
- `--namespace`: The PHP namespace for the plugin’s objects (for example, `WonderWp\Plugin\MyPluginNameSpace`).

**Optional parameters – plugin information:**

- `--desc`: Short plugin description (keep under 140 characters).
- `--uri`: Plugin homepage URL (must be unique, cannot be a WordPress.org URL).
- `--version`: Plugin version string (for example, `1.0` or `1.0.3`).

**Optional parameters – author information:**

- `--author`: Plugin author name(s); multiple authors can be comma-separated.
- `--author_uri`: Author website or WordPress.org profile URL.

**Optional parameters – license information:**

- `--licence`: Short name/slug of the plugin license (for example, `GPL2`).
- `--licence_uri`: URL to the full license text (for example, `https://www.gnu.org/licenses/gpl-2.0.html`).

**Optional parameters – internationalization:**

- `--textdomain`: Gettext text domain for translations.
- `--domain_path`: Path where WordPress can find translations.

**Plugin type:**

- The generator supports multiple output types, but this skill focuses on the `basic` plugin type.
- If optional parameters are not provided, the generator uses sensible defaults.

## Workflow

### Step 1: Collect parameters and run the generator

**Do:**

1. Ensure required parameters (`--name`, `--namespace`) are collected and validated with the user.
2. Ask for any useful optional parameters from the groups in **Rules** (plugin information, author, license, internationalization) and pass them as flags when available.
3. Run the generation command, for example:

   ```bash
   wp wonderwp:generate-plugin --name="myPluginName" --desc="This is my plugin description" --namespace="WonderWp\Plugin\MyPluginNameSpace"
   ```

4. The generator will:
   - Validate the provided parameters.
   - Create the appropriate plugin structure for the chosen output type (here, `basic`).
   - Display a success message if everything is correct.

**Success criteria:** Generator completes without errors and plugin folder exists.

### Step 2: Validation and debugging

**Do:**

- If the generation fails or the user reports issues, rerun the command with the debug flag:

  ```bash
  wp wonderwp:generate-plugin --name="myPluginName" --namespace="WonderWp\Plugin\MyPluginNameSpace" --debug=generator
  ```

- Use the debug output to identify and correct configuration errors (missing parameters, invalid namespaces, filesystem issues, etc.).

**Success criteria:** Any generator failures are resolved using debug output.

### Step 3: Register the plugin (Composer autoload)

**Do:**

After generation, the plugin namespace must be added to the project’s `composer.json` in the `autoload` section.  
The default autoload root is the plugin’s `includes` folder.

Example configuration:

```json
"autoload": {
  "psr-4": {
    "WonderWp\\Plugin\\MyPluginNameSpace\\": "path/to/plugin/includes/"
  }
}
```

Once the autoload entry is added or updated, regenerate Composer autoload files:

```bash
composer dump-autoload
```

**Success criteria:** `composer.json` PSR-4 maps to the plugin `includes/` and `dump-autoload` succeeds.

### Step 4: Activate the plugin

**Do:**

When the plugin is registered via Composer, it can be activated with WP-CLI:

```bash
wp plugin activate myPluginName
```

Verify that:

- The plugin appears in the WordPress admin Plugins list.
- Activation succeeds without errors.

**Success criteria:** Plugin is active in WP with no activation errors.

### Step 5: Documentation, gitignore, and commit preparation

**Documentation:**

- Look for a `readme.md` file in the plugin root.
- If none exists, create one with:
  - The plugin name as the title.
  - The plugin description as a short introduction.
- Keep the initial readme minimal; more sections can be added later.

**Git ignore and commit preparation:**

- By default, the root `.gitignore` ignores all plugins.
- Because this plugin is user-generated and should be tracked, add a specific unignore rule for its root folder.
- In the root `.gitignore`, either:
  - Find an existing plugin section and add the unignore rule there, or
  - Append a new rule at the end of the file.

The unignore rule should look like:

```gitignore
!/web/app/plugins/my-newly-created-plugin
```

Once the plugin is properly unignored:

- Stage the plugin files and any `.gitignore` changes.
- Propose a clear commit message describing the new plugin addition.

**Success criteria:** Minimal readme exists; plugin path is unignored; changes are ready to commit.

## Advanced Features

### Example 1 – Basic plugin generation

User intent:

> \"I want to create a new WonderWP plugin that I can activate in my project.\"

Typical command:

```bash
wp wonderwp:generate-plugin \
  --name="myPluginName" \
  --desc="This is my plugin description" \
  --namespace="WonderWp\Plugin\MyPluginNameSpace"
```

Follow-on steps:

1. Add the plugin namespace to `composer.json` under `autoload.psr-4`.
2. Run `composer dump-autoload`.
3. Activate the plugin with:

   ```bash
   wp plugin activate myPluginName
   ```

4. Add an unignore rule for the plugin in `.gitignore` and propose committing the new plugin.

### Follow-ups

After successfully creating and activating the plugin, suggest logical follow-ups, for example:

- Ask whether the user wants to generate a **custom post type** for this plugin.
- Ask whether they want to add **custom fields**, **taxonomies**, or **hooks** using the corresponding WonderWP backend skills.
