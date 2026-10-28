---
name: wiring-doctrine-manager-and-services
description: >-
  Wires a WonderWP Doctrine plugin: AbstractDoctrinePluginManager, Activator createTables,
  HookService with DoctrineHookServiceTrait, and DoctrineEMLoaderService. Use early after
  the plugin bootstrap shell exists, then revisit after entity/form/repository/listtable skills.
metadata:
  domain: wonderwp-doctrine
---

# Wiring Doctrine manager and services

## Goal

Convert a WonderWP plugin shell into a Doctrine-backed plugin by wiring Manager, Activator, HookService, and EM loader using WonderWP Core contracts and skill templates.

## Rules

- **Prerequisite:** plugin bootstrap exists under `web/app/plugins/{Slug}/` (see **wonderwp-backend** `creating-a-new-plugin` or an existing bootstrap in the consumer project). Read [doctrine-archetype.md](../../references/doctrine-archetype.md) and collect placeholders **once**.
- Run this skill **early** (before or in parallel with entity work); **revisit** after Form, Repository, and ListTable skills to ensure all classes are registered.
- **Full-class templates** (copy/adapt when `{Plugin}Manager.php` or services do not exist yet):
  - [DoctrinePluginManager.php.template](./assets/DoctrinePluginManager.php.template)
  - [DoctrinePluginActivator.php.template](./assets/DoctrinePluginActivator.php.template)
  - [DoctrinePluginHookService.php.template](./assets/DoctrinePluginHookService.php.template)
  - [DoctrineEMLoaderService.php.template](./assets/DoctrineEMLoaderService.php.template)
  - [EntityPersisterService.php.template](./assets/EntityPersisterService.php.template) (when domain services need writes)
- **Merge snippets** (when classes already exist and you only add registrations or entities):
  - [Manager.register.snippet.php](./assets/Manager.register.snippet.php)
  - [Manager.business-services.snippet.php](./assets/Manager.business-services.snippet.php)
  - [Activator.createTables.snippet.php](./assets/Activator.createTables.snippet.php)
  - [HookService.doctrine.snippet.php](./assets/HookService.doctrine.snippet.php)
- Manager **must** extend `AbstractDoctrinePluginManager`.
- Placeholders: `{Slug}`, `{Namespace}`, `{Plugin}`, `{PluginConst}`, `{Entity}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).

## Workflow

### Step 0: Create Doctrine shell classes (when missing)

**Do:**

1. Read [doctrine-archetype.md](../../references/doctrine-archetype.md); confirm `{Namespace}`, `{Plugin}`, `{PluginConst}`, `{Entity}`, `{Slug}`.
2. If `includes/{Plugin}Manager.php` does not exist, create it from [DoctrinePluginManager.php.template](./assets/DoctrinePluginManager.php.template).
3. Ensure `includes/Service/` exists; create from templates if missing:
   - `{Plugin}Activator.php` ← [DoctrinePluginActivator.php.template](./assets/DoctrinePluginActivator.php.template)
   - `{Plugin}HookService.php` ← [DoctrinePluginHookService.php.template](./assets/DoctrinePluginHookService.php.template)
   - `{Plugin}DoctrineEMLoaderService.php` ← [DoctrineEMLoaderService.php.template](./assets/DoctrineEMLoaderService.php.template)
4. Replace all placeholders consistently (same values as entity/form/listtable skills).
5. Add `languages/` folder (can be empty with `.gitkeep`) so Activator `copyLanguageFiles()` does not fail.

**Success criteria:** Manager and three Service classes exist with correct namespaces; no `{Placeholder}` literals remain.

### Step 1: Change Manager base class (existing basic Manager only)

**Do:**

1. Open `includes/{Plugin}Manager.php`.
2. If it extends `AbstractManager` instead of `AbstractDoctrinePluginManager`, change the parent and `use` statements.
3. If Step 0 created the Manager from template, skip to Step 2 verification.

**Success criteria:** Manager extends `AbstractDoctrinePluginManager`.

### Step 2: Merge or verify register() wiring

**Do:**

1. If Manager was created from **DoctrinePluginManager.php.template**, verify entity/form/controller class names match created files.
2. Otherwise merge [Manager.register.snippet.php](./assets/Manager.register.snippet.php) into `register()` after `parent::register($container)`.
3. Keep `path.root`, `path.base`, `path.url` config from the basic plugin if already present.

**Success criteria:** `entityName`, `formName`, repository, form, list table, activator, hook, and EM loader services registered.

### Step 3: Activator

**Do:**

1. Ensure `{Plugin}Activator` extends `AbstractDoctrinePluginActivator`.
2. If created from template, confirm `createTables([{Entity}::class])` lists every entity.
3. Otherwise apply [Activator.createTables.snippet.php](./assets/Activator.createTables.snippet.php).

**Success criteria:** plugin activation creates Doctrine tables.

### Step 4: HookService

**Do:**

1. Ensure `{Plugin}HookService` extends `AbstractHookService` and uses `DoctrineHookServiceTrait`.
2. Confirm `register()` calls `$this->registerDoctrinePluginHooks()`.
3. Implement **`customizeMenus()`** (required): `DoctrineHookServiceTrait` does **not** add the admin menu; copy from [DoctrinePluginHookService.php.template](./assets/DoctrinePluginHookService.php.template). `loadTextdomain()` is inherited from `AbstractHookService`.
4. Keep domain-specific hooks in separate **creating-a-new-hook** skills.

**Success criteria:** admin menu entry under `wonderwp-modules` and Doctrine CRUD hooks fire.

### Step 5: Doctrine EM loader

**Do:**

1. Confirm `{Plugin}DoctrineEMLoaderService.php` registers the `includes/Entity/` path.
2. Confirm Manager registers `DoctrineEMLoaderServiceInterface::DOCTRINE_EM_LOADER_SERVICE_NAME`.

**Success criteria:** entity metadata loads from `includes/Entity/`.

### Step 6: Revisit after artefact skills

**Do:**

After **creating-a-doctrine-entity**, **form**, **repository**, **listtable**, **admin-controller**:

1. Update Activator entity list.
2. Confirm Manager class names match created files.
3. Run `composer dump-autoload` if namespaces changed.
4. `wp plugin deactivate {Slug} && wp plugin activate {Slug}` to run Activator.

**Success criteria:** full Doctrine CRUD path works in admin.

## Anti-patterns

- Do not register Doctrine services on a plain `AbstractManager` — use `AbstractDoctrinePluginManager`.
- Do not skip `registerDoctrinePluginHooks()` — entity metadata will not load in Doctrine.
- Do not register `customizeMenus` without implementing it — `DoctrineHookServiceTrait` only registers the plugin as Doctrine-backed.
- Do not invent bootstrap or Manager structure when templates exist — copy/adapt skill assets.
- Do not use `{PluginConst}` where `{Slug}` is required (folder names, `wp plugin activate`, gitignore).
- Do not call `EntityManager::getInstance()->find()` / `persist()` / `flush()` from domain services — use a **Persister** service (see [EntityPersisterService.php.template](./assets/EntityPersisterService.php.template)) and repositories for reads.
- Do not pass string literals to `addService()` / `getService()` for plugin-owned services — declare `public const` on the Manager and use those constants (reuse `ServiceInterface::*` when they already exist).
- Do not inject a resolved Doctrine repository into long-lived service constructors at Manager wiring time — inject `DoctrineRepositoryServiceResolver` or `ManagerServiceResolver` and call `->resolve()` at use time (EntityManager may not be ready yet).

## Business services conventions

When adding domain services beyond CRUD shell:

1. **Service name constants** on `{Plugin}Manager` for every custom `addService` / `getService` key.
2. **Persister** for writes (`persist` + `flush`); domain services never touch `EntityManager` directly.
3. **Repository access** via `DoctrineRepositoryServiceResolver` / `ManagerServiceResolver` injected into services:

```php
$this->addService(self::MY_DOMAIN_SERVICE, function () {
    return new MyDomainService(
        new DoctrineRepositoryServiceResolver($this, ServiceInterface::REPOSITORY_SERVICE_NAME),
        $this->getService(self::ENTITY_PERSISTER_SERVICE)
    );
});
```

See [Manager.business-services.snippet.php](./assets/Manager.business-services.snippet.php).

## Follow-ups

Suggest next skills: **creating-a-doctrine-entity** → repository → form → listtable → admin-controller → **creating-a-new-hook** for business logic.
