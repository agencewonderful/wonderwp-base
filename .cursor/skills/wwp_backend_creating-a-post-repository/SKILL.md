---
name: creating-a-post-repository
description: >-
  Creates a WonderWP post repository class and registers it in the plugin
  manager for a given post type. Use when the user wants a dedicated repository
  service for querying a specific custom post type.
metadata:
  domain: wonderwp-backend
---

# Creating a Post Repository

## Goal

Add a `PostRepository` subclass for a CPT and register it on the plugin manager as `ServiceInterface::REPOSITORY_SERVICE_NAME`.

## Rules

- **Mandatory:** the plugin where the repository should be created; the post type the repository will manage.

## Workflow

### Step 1: Prepare the folder

**Do:**

1. Determine the plugin folder.
2. Check for an `includes/Repositories` folder:
   - If it does not exist, create it.

**Success criteria:** `includes/Repositories` exists.

### Step 2: Generate the repository class

**Do:**

Create a repository class within `includes/Repositories` following this pattern:

```php
<?php

namespace MyPluginNamespace\Repositories;

use WonderWp\Plugin\Core\Framework\Repository\PostRepository;

class MyPluginPostRepository extends PostRepository
{
    const POST_TYPE = 'my-post-type-key';
}
```

Adapt:

- Namespace (`MyPluginNamespace\Repositories`) to match the plugin.
- Class name (e.g. `MyPluginPostRepository`) to reflect the plugin or entity.
- `POST_TYPE` constant to the actual custom post type key.

**Success criteria:** Repository class exists with correct `POST_TYPE` and namespace.

### Step 3: Locate the plugin manager

**Do:**

1. In the plugin’s `includes` folder, locate the manager class (e.g. `MyPluginManager.php`).
2. Open the manager and search for an existing repository service registration:

```php
$this->addService(ServiceInterface::REPOSITORY_SERVICE_NAME
```

- If this string is present, a repository may already exist:
  - Ask the user to confirm whether the repository is already declared and sufficient.
- If not present, continue to register the new repository service.

**Success criteria:** You know whether a repository service already exists or you will add one.

### Step 4: Add the repository service

**Do:**

In the manager’s registration logic, add:

```php
$this->addService(ServiceInterface::REPOSITORY_SERVICE_NAME, function () {
    return new MyPluginPostRepository();
});
```

Ensure:

- `MyPluginPostRepository` is correctly imported via a `use` statement.
- The service name `ServiceInterface::REPOSITORY_SERVICE_NAME` is used consistently, as it is expected by other WonderWP components.

**Success criteria:** Manager registers the repository under the standard service key.

### Step 5: Document and ship

**Documentation:**

- Locate the plugin’s `README.md`.
- Add or update a “Repositories” section.
- Document the new repository:
  - Which post type it is tied to.
  - Any noteworthy query behavior or usage patterns.

**Proposing a commit:**

- Once the repository class and manager registration are in place:
  - Propose a commit message summarizing:
    - Creation of the repository class.
    - Registration in the manager.
    - README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README documents repository purpose; commit proposed.

## Advanced Features

### Example – Repository for a News CPT

User intent:

> \"I want a repository for my `news` custom post type, so I can query it cleanly.\"

Flow:

1. Plugin and CPT key: e.g. plugin `Actu`, CPT key `news`.
2. Create `includes/Repositories/ActuPostRepository.php` extending `PostRepository` with `const POST_TYPE = 'news';`.
3. In the plugin manager, register the repository under `ServiceInterface::REPOSITORY_SERVICE_NAME`.
4. Document the new repository in the plugin README’s “Repositories” section.

### Follow-ups

After creating a post repository, the user might:

- Use it in hooks, liaison fields, importer syncers, or other services.
- Add custom query methods for common access patterns.

Ask how they want to leverage the repository next and suggest related WonderWP skills (e.g. liaison fields, importer architecture).
