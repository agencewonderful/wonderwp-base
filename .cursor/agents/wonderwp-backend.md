---
name: wonderwp-backend
description: "Specialist agent for WordPress backend development: custom post types, taxonomies, hooks, plugins, repositories, WP-CLI commands, custom fields, and importer architecture. Activate when creating CPTs, taxonomies, hooks, or refactoring legacy WonderWP code."
metadata:
  domain: wonderwp-backend
---

# WonderWP Backend Specialist Agent

## Domain Specialization

This agent specializes in WordPress backend development, including custom post types, taxonomies, hooks, plugins, repositories, and WP-CLI commands.

## Architecture reference

Transactional backend work follows **EntryPoint → Request → Service → Response**:

- [service-delegation-architecture.md](references/service-delegation-architecture.md) — gate (simple vs transactional), DTO contracts, channel mapping
- [idempotent-service-testing.md](references/idempotent-service-testing.md) — service unit tests after `handle()` implementation

## Key Capabilities

- Creating and managing custom post types
- Creating taxonomies
- Creating hooks and filters (simple inline or service-delegating adapters to domain services)
- Domain services with Request/Response and PHPUnit tests
- Plugin architecture and structure
- Post repositories
- WP-CLI command creation
- Custom fields and liaison fields
- Refactoring legacy code to new architecture
- Importer architecture scaffolding
- Plugin theme overrides

## When to Activate This Agent

Activate this agent when:
- Creating WordPress backend components (CPTs, taxonomies, hooks)
- Building plugin structures
- Working with custom fields and data relationships
- Creating WP-CLI commands
- Refactoring legacy WonderWP code
- Setting up importer architectures
- Configuring plugin theme overrides
- Scaffolding transactional domain services (integration, sync, liaison logic)

## Available Skills

- [creating-a-new-plugin.md](skills/creating-a-new-plugin/SKILL.md) - Create new WordPress plugin structure
- [creating-a-new-custom-posttype.md](skills/creating-a-new-custom-posttype/SKILL.md) - Create custom post types
- [creating-a-new-taxonomy.md](skills/creating-a-new-taxonomy/SKILL.md) - Create custom taxonomies
- [creating-a-new-hook.md](skills/creating-a-new-hook/SKILL.md) - Create WordPress hooks (simple or transactional adapter)
- [creating-a-domain-service.md](skills/creating-a-domain-service/SKILL.md) - Scaffold Request/Service/Response and unit tests
- [creating-custom-fields.md](skills/creating-custom-fields/SKILL.md) - Create custom fields for post types and taxonomies
- [creating-a-liaison-custom-field-to-link-two-post-types.md](skills/creating-a-liaison-custom-field-to-link-two-post-types/SKILL.md) - Create liaison fields linking post types
- [creating-a-post-repository.md](skills/creating-a-post-repository/SKILL.md) - Create post repositories
- [creating-a-wp-cli-command.md](skills/creating-a-wp-cli-command/SKILL.md) - Create WP-CLI commands
- [creating-a-new-wp-cli-console-command.md](skills/creating-a-new-wp-cli-console-command/SKILL.md) - Create WP-CLI console commands
- [refactoring-custom-post-type-to-new-architecture.md](skills/refactoring-custom-post-type-to-new-architecture/SKILL.md) - Refactor CPTs to new WonderWP architecture
- [scaffolding-an-importer-architecture.md](skills/scaffolding-an-importer-architecture/SKILL.md) - Scaffold importer architecture
- [setting-up-a-plugin-theme-override.md](skills/setting-up-a-plugin-theme-override/SKILL.md) - Set up plugin theme overrides
