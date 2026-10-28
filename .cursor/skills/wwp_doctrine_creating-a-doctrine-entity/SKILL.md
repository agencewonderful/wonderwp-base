---
name: creating-a-doctrine-entity
description: >-
  Creates a WonderWP Doctrine entity (AbstractEntity) from a business model in
  greenfield mode: ORM annotations, table mapping, fields, indexes, and getters/setters.
  Use when adding a new persisted entity to a Doctrine plugin or designing schema before createTables().
metadata:
  domain: wonderwp-doctrine
---

# Creating a Doctrine entity

## Goal

Produce `includes/Entity/{Entity}.php` extending `AbstractEntity` with Doctrine ORM annotations so `Activator::createTables()` can create the schema.

## Rules

- **Prerequisite:** plugin bootstrap + **wiring-doctrine-manager-and-services** (Manager extends `AbstractDoctrinePluginManager`).
- **Greenfield mode:** model the business domain first; do not require a pre-existing SQL table.
- **Template:** copy and adapt [assets/Entity.php.template](./assets/Entity.php.template) — do not invent a different class structure.
- **Placeholders:** `{Namespace}`, `{Entity}`, `{Table}`, `{RepositoryClass}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).
- Map `enabled` / status fields consistently with ListTable expectations (often a non-persisted `$enabled` property on `AbstractEntity` subclasses, or a persisted `statut` column — pick one approach and document it).
- For JSON blobs vs normalized tables (e.g. embedded guest contacts), prefer a dedicated associated entity when queries/filtering are needed; use `json` column type only for opaque payloads.

## Workflow

### Step 1: Collect the model

**Do:**

1. Confirm plugin slug, PHP namespace (`{Namespace}`), entity name (`{Entity}`), table name (`{Table}`).
2. List fields: name, Doctrine type (`string`, `text`, `integer`, `boolean`, `datetime`, `json`, …), length, nullable, default.
3. Note indexes, unique constraints, and relations (`ManyToOne`, etc.) if any.
4. Identify status/enabled behaviour for admin ListTable.

**Success criteria:** field list and table name are agreed.

### Step 2: Create the Entity folder

**Do:**

1. Ensure `includes/Entity/` exists in the plugin.
2. Create `{Entity}.php` from [assets/Entity.php.template](./assets/Entity.php.template).

**Success criteria:** file path is `includes/Entity/{Entity}.php`.

### Step 3: Fill ORM annotations

**Do:**

1. Replace placeholders in the template.
2. Replace `{EntityFields}` with `@ORM\Column` properties (private fields).
3. Replace `{EntityFieldsIndexes}` with `@ORM\Index` entries where needed.
4. Set `@ORM\Entity(repositoryClass="…")` to `{RepositoryClass}` (create Repository in a later skill if missing).
5. Add relation annotations if applicable.

**Success criteria:** every persisted field has column mapping; id column uses `AUTO` strategy.

### Step 4: Add getters and setters

**Do:**

1. Replace `{EntityGettersSetters}` with fluent setters returning `$this` (match existing plugin style, e.g. `wwp-tracker`).
2. Add `isEnabled()` / `setEnabled()` when using the enabled flag pattern.

**Success criteria:** all mapped fields are accessible; no public properties.

### Step 5: Wire Activator (if not done)

**Do:**

1. Open the plugin Activator (from **wiring-doctrine-manager-and-services**).
2. Ensure `createTables([{Entity}::class])` includes the new entity class.

**Success criteria:** activation creates or updates the table.

### Step 6: Suggest follow-ups

**Do:**

Propose the next skills in order: **creating-a-doctrine-form**, **creating-a-doctrine-repository**, **creating-a-doctrine-listtable**.

**Success criteria:** user knows the next orchestration step.

## Anti-patterns

- Do not use CPT storage when the epic requires Doctrine tables.
- Do not embed full template PHP in this skill — always start from `assets/Entity.php.template`.
- Do not skip `repositoryClass` on `@ORM\Entity` when a custom repository is planned.
