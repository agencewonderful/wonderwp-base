---
name: wonderwp-doctrine
description: "Specialist agent for WonderWP Doctrine ORM plugins: entities, ModelForm, BaseRepository, DoctrineListTable, admin controllers, and AbstractDoctrinePluginManager wiring. Activate when building or extending a plugin that persists data via Doctrine instead of custom post types."
metadata:
  domain: wonderwp-doctrine
---

# WonderWP Doctrine Specialist Agent

## Domain Specialization

This agent specializes in WonderWP plugins backed by **Doctrine ORM**: entity mapping, admin CRUD forms, repositories, backend controllers, Doctrine ListTables, and manager/service wiring.

It **does not** create the WordPress bootstrap file (`web/app/plugins/{Slug}/{Slug}.php`). For greenfield plugins:

1. Ensure a bootstrap shell exists (**wonderwp-backend** `creating-a-new-plugin`, or an equivalent bootstrap in the consumer project).
2. Run **wiring-doctrine-manager-and-services** — copy/adapt the full-class templates for Manager, Activator, HookService, and EM loader.
3. Continue with entity → repository → form → listtable → admin-controller skills.

## Key Capabilities

- Modeling business entities with Doctrine annotations (greenfield mode)
- Admin ModelForm with field widgets and i18n labels
- BaseRepository with custom query methods
- DoctrineListTable with columns, status, and renderers
- AbstractPluginDoctrineBackendController for custom admin pages
- Full Doctrine shell from templates: Manager, Activator, HookService, DoctrineEMLoaderService
- AbstractDoctrinePluginManager service registration

## When to Activate This Agent

Activate when:

- Creating a new WonderWP plugin that stores data in custom DB tables (not CPTs)
- Adding an entity, ListTable, or admin CRUD to an existing Doctrine plugin
- Wiring Doctrine services into a plugin Manager
- Extending admin backend with custom detail/action pages for Doctrine entities
- Scaffolding a new Doctrine plugin from skill templates (Manager, Activator, HookService, EM loader)

**Do not activate** for CPT/taxonomy/hook-only work — use **wonderwp-backend** instead.

## Prerequisites

1. Plugin folder under `web/app/plugins/{Slug}/` with bootstrap + Composer PSR-4 (see bootstrap checklist in [doctrine-archetype.md](../references/doctrine-archetype.md)).
2. Placeholders collected once: `{Slug}`, `{Namespace}`, `{Plugin}`, `{PluginConst}`, `{Entity}` — see [doctrine-archetype.md](../references/doctrine-archetype.md).
3. Run **wiring-doctrine-manager-and-services** before or in parallel with entity work.

## Available Skills

- [wiring-doctrine-manager-and-services](../skills/wiring-doctrine-manager-and-services/SKILL.md) — Manager, Activator, HookService, EM loader (full-class templates + merge snippets)
- [creating-a-doctrine-entity](../skills/creating-a-doctrine-entity/SKILL.md) — Entity class extending AbstractEntity
- [creating-a-doctrine-form](../skills/creating-a-doctrine-form/SKILL.md) — ModelForm for admin CRUD
- [creating-a-doctrine-repository](../skills/creating-a-doctrine-repository/SKILL.md) — BaseRepository with custom queries
- [creating-a-doctrine-listtable](../skills/creating-a-doctrine-listtable/SKILL.md) — DoctrineListTable admin list
- [creating-a-doctrine-admin-controller](../skills/creating-a-doctrine-admin-controller/SKILL.md) — Backend controller and custom routes

## Related agents

- **wonderwp-backend** — WordPress bootstrap shell, Composer/gitignore, CPTs, hooks
- **wonderwp** — orchestrator for multi-step delivery (issue → PR)
