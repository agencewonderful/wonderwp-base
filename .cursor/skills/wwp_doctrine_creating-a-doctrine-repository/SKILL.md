---
name: creating-a-doctrine-repository
description: >-
  Creates a WonderWP Doctrine BaseRepository for an entity, including optional custom
  query methods (findByStatut, filters, etc.). Use when adding data access for a Doctrine entity.
metadata:
  domain: wonderwp-doctrine
---

# Creating a Doctrine repository

## Goal

Produce `includes/Repository/{Entity}Repository.php` extending `BaseRepository` and register it on the Manager.

## Rules

- **Prerequisite:** entity exists (**creating-a-doctrine-entity**).
- **Template:** [assets/EntityRepository.php.template](./assets/EntityRepository.php.template).
- **Placeholders:** `{Namespace}`, `{Entity}`, `{RepositoryClass}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).
- Entity `@ORM\Entity(repositoryClass="…")` must reference this class.
- Manager registers repository as:

```php
$this->addService(ServiceInterface::REPOSITORY_SERVICE_NAME, function () {
    return new {Entity}Repository(null, null, $this->getConfig('entityName'));
});
```

- Use `$this->createQueryBuilder('alias')` for custom DQL; alias should be short (e.g. `e`, `t`).

## Workflow

### Step 1: Confirm entity and namespace

**Do:**

1. Verify `{Entity}` class and `{Namespace}`.
2. List required custom queries (by status, date, relation, etc.).

**Success criteria:** query requirements documented.

### Step 2: Create repository class

**Do:**

1. Ensure `includes/Repository/` exists.
2. Copy [assets/EntityRepository.php.template](./assets/EntityRepository.php.template) to `{Entity}Repository.php`.
3. Replace placeholders.
4. Implement custom methods with typed parameters and documented return types where practical.

**Success criteria:** class extends `BaseRepository`; entity annotation points here.

### Step 3: Register on Manager

**Do:**

1. Add or verify `REPOSITORY_SERVICE_NAME` registration in `{Plugin}Manager.php` (**wiring-doctrine-manager-and-services**).
2. Ensure `entityName` config is `{Entity}::class`.

**Success criteria:** `$manager->getService(ServiceInterface::REPOSITORY_SERVICE_NAME)` returns the repository.

### Step 4: Usage in hooks/controllers

**Do:**

If hooks or admin controllers need the repository, inject via manager service — do not instantiate manually.

**Success criteria:** repository is the single data-access entry point for the entity.

## Reference

See `TrackerRepository::findByLocale()` in `wwp-tracker` for a custom query example.

## Anti-patterns

- Do not extend `PostRepository` — that is for CPTs (**wonderwp-backend**).
- Do not embed SQL strings — use the query builder.
