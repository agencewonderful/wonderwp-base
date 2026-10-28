---
name: scaffolding-an-importer-architecture
description: >-
  Scaffolds a complete WonderWP importer architecture, including command,
  importer, repositories, transformers, syncer, and manager wiring, based on
  the import-foundation package. Use when the user wants to import data from
  an external source into WordPress using a structured importer stack.
metadata:
  domain: wonderwp-backend
---

# Scaffolding an importer architecture

## Goal

Scaffold a full WonderWP importer stack (WP-CLI command, importer, repositories, transformers, syncer, manager services) using `wonderwp/import-foundation` conventions.

## Rules

> An importer architecture is a set of classes used to import data from an external source into a WordPress site. It typically includes:
>
> - `Commands/ImporterCommand` – WP-CLI command to execute the import.
> - `Importer/Importer` – Main importer orchestrating the process.
> - `Repositories/SourceRepository` – Reads data from the external source.
> - `Transformers/SourceTransformer` – Transforms source data into an internal model.
> - `Repositories/DestinationRepository` – Writes to WordPress or other storage.
> - `Transformers/DestinationTransformer` – Transforms internal model into destination format.
> - `Syncers/Syncer` – Synchronizes between source and destination.
> - `Manager` – Registers all services in the DI container.

- **Naming conventions:**
  - `[PluginName]`: e.g. `Actu`, `Formations`.
  - `[Entity]`: e.g. `News`, `Projects`, `Alumni`.
  - `[plugin-slug]`: e.g. `wwp-actu`, `wwp-formations`.
  - `[entity-slug]`: e.g. `esma-art-news`, `esma-projects`.
  - `[PLUGIN_CONSTANT]`: e.g. `WWP_PLUGIN_ACTU_NAME`.
- Confirm with the user before making any Composer changes.
- The **Importer** orchestrates full import runs. Ad-hoc integration hooks (webhooks, filters, one-off sync) must delegate to a **domain service** (`creating-a-domain-service`) — do not reimplement import/sync logic inside hooks. See [service-delegation-architecture.md](../../references/service-delegation-architecture.md).

## Workflow

### Step 1: WonderWP import-foundation package

**Do:**

1. Check if the `vendor/wonderwp/import-foundation` folder exists:
   - If yes, the package is installed and ready.
2. If not, inspect `composer.json`:
   - If the package is listed, run:

     ```bash
     composer install
     ```

   - If not listed, install it:

     ```bash
     composer require wonderwp/import-foundation:dev-develop@dev
     ```

Confirm with the user before making any Composer changes.

**Success criteria:** `import-foundation` is present in `vendor` or the user has approved Composer changes.

### Step 2: Create the directory structure

**Do:**

In the target plugin’s `includes` folder, ensure this structure exists:

```text
includes/
├── Commands/
├── Importer/
├── Repositories/
├── Transformers/
├── Syncers/
└── [YourPlugin]Manager.php
```

Create any missing directories.

**Success criteria:** Folder layout exists under `includes/`.

### Step 3: Create the Command class

**Do:**

Create `includes/Commands/Import[Entity]Command.php` (for example, `ImportNewsCommand.php`) from [assets/ImportEntityCommand.php.tpl](assets/ImportEntityCommand.php.tpl).

Key points:

- The command should extend `AbstractImporterCommand`.
- It must define a name/signature and description.
- It must load the importer service from the plugin manager.

Follow the general WP-CLI guidance in the **creating-a-wp-cli-command** skill for logging, argument definitions, and bootstrap patterns.

**Success criteria:** Command class extends `AbstractImporterCommand` and resolves the importer from the manager.

### Step 4: Create the Importer class

**Do:**

Create `includes/Importer/[Entity]Importer.php` from [assets/EntityImporter.php.tpl](assets/EntityImporter.php.tpl).

This class will later be wired with source/destination repositories, transformers, and syncer via the manager.

**Success criteria:** Importer stub extends `AbstractImporter`.

### Step 5: Create the Source Repository

**Do:**

Create `includes/Repositories/[Entity]SourceRepository.php` from [assets/EntitySourceRepository.php.tpl](assets/EntitySourceRepository.php.tpl).

Fill in the methods based on the external data source.

**Success criteria:** Source repository implements `RepositoryInterface` with stubs/TODOs for the real source.

### Step 6: Create the Destination Repository

**Do:**

Create `includes/Repositories/[Entity]DestinationRepository.php` from [assets/EntityDestinationRepository.php.tpl](assets/EntityDestinationRepository.php.tpl).

Adapt the implementation to how data should be persisted in WordPress.

**Success criteria:** Destination repository stub matches persistence needs.

### Step 7: Create the Source Transformer

**Do:**

Create `includes/Transformers/[Entity]SourceTransformer.php` from [assets/EntitySourceTransformer.php.tpl](assets/EntitySourceTransformer.php.tpl).

**Success criteria:** Source transformer class extends `AbstractTransformer`.

### Step 8: Create the Destination Transformer

**Do:**

Create `includes/Transformers/[Entity]DestinationTransformer.php` from [assets/EntityDestinationTransformer.php.tpl](assets/EntityDestinationTransformer.php.tpl).

**Success criteria:** Destination transformer class extends `AbstractTransformer`.

### Step 9: Create the Syncer

**Do:**

Create `includes/Syncers/[Entity]Syncer.php` from [assets/EntitySyncer.php.tpl](assets/EntitySyncer.php.tpl).

**Success criteria:** Syncer extends `AbstractPostsSyncer`.

### Step 10: Create or update the Manager class

**Do:**

Create or update the plugin’s manager (for example, `includes/[PluginName]ThemeManager.php`) from [assets/ThemeManagerImporterWiring.php.tpl](assets/ThemeManagerImporterWiring.php.tpl).

Replace:

- `[PluginName]` with the actual plugin name.
- `[Entity]` with the import entity (e.g. `News`).
- `[plugin-slug]`, `[entity-slug]`, and `[PLUGIN_CONSTANT]` with project-specific values.

**Success criteria:** Manager registers importer, repos, transformers, persister, and syncer with consistent service keys.

### Step 11: Usage and testing

**Do:**

After scaffolding:

1. Ensure the manager is registered and used by the project.
2. Implement the TODOs in the repositories and transformers for your specific data model.
3. Run the import command:

```bash
wp [plugin-slug]:import-[entity]
```

4. Verify that:
   - Data is fetched from the external source.
   - Data is transformed and persisted as expected.
   - Logs and errors are clear.

**Success criteria:** Command runs; data flows source → transform → destination as expected.

## Advanced Features

### Example – News importer for Actu plugin

User intent:

> \"I want an importer that syncs News from an external API into my Actu plugin.\"

Flow:

1. Confirm the `wonderwp/import-foundation` package is installed (or install it).
2. Scaffold:
   - `ImportNewsCommand`.
   - `NewsImporter`.
   - `NewsSourceRepository` and `NewsDestinationRepository`.
   - `NewsSourceTransformer` and `NewsDestinationTransformer`.
   - `NewsSyncer`.
3. Wire everything in `ActuThemeManager`.
4. Implement repository and transformer logic for the News API.
5. Test with `wp wwp-actu:import-news`.

### Follow-ups

After scaffolding the importer architecture, follow-up tasks include:

- Filling in real data access logic in repositories.
- Implementing transformation rules for incoming and outgoing data.
- Adding logging, error handling, and dry-run capabilities to the command.

These can leverage patterns from your generic WP-CLI command skill and other WonderWP backend skills.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/ImportEntityCommand.php.tpl](assets/ImportEntityCommand.php.tpl) | WP-CLI importer command |
| Template | [assets/EntityImporter.php.tpl](assets/EntityImporter.php.tpl) | Main importer class |
| Template | [assets/EntitySourceRepository.php.tpl](assets/EntitySourceRepository.php.tpl) | External source repository |
| Template | [assets/EntityDestinationRepository.php.tpl](assets/EntityDestinationRepository.php.tpl) | WordPress destination repository |
| Template | [assets/EntitySourceTransformer.php.tpl](assets/EntitySourceTransformer.php.tpl) | Source data transformer |
| Template | [assets/EntityDestinationTransformer.php.tpl](assets/EntityDestinationTransformer.php.tpl) | Destination data transformer |
| Template | [assets/EntitySyncer.php.tpl](assets/EntitySyncer.php.tpl) | Posts syncer |
| Template | [assets/ThemeManagerImporterWiring.php.tpl](assets/ThemeManagerImporterWiring.php.tpl) | Manager service registration |
