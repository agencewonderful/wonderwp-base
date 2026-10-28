---
name: creating-a-liaison-custom-field-to-link-two-post-types
description: >-
  Creates a liaison custom field that links two post types via a select field,
  including custom fields integration, hook wiring, and repository usage.
  Use when the user wants to relate posts of one post type to another within
  WonderWP plugins.
metadata:
  domain: wonderwp-backend
---

# Creating a Liaison Custom Field to Link Two Post Types

## Goal

Add a liaison select field on a destination CPT’s custom fields, implemented in the **source** plugin via a hook on the destination’s meta-definitions filter, using a repository to list source posts.

## Rules

- **Mandatory:** destination plugin (where the liaison field appears in the UI); source plugin (which provides the post type to link to and where liaison logic lives).
- **Optional:** label for the liaison field (if not provided, extrapolate from the source post type name).
- The **source** plugin acts on the **destination** plugin: the source plugin’s code injects the liaison field into the destination plugin’s custom fields.
- Liaison field building (repository + `SelectField`) is **transactional** business logic: use **creating-a-domain-service** for `{Liaison}Request` / `{Liaison}Response` / `{Liaison}Service`; the hook only registers the meta key and delegates the field factory to the service. See [service-delegation-architecture.md](../../references/service-delegation-architecture.md).

## Workflow

### Step 1: PostTypes availability

**Do:**

1. Ensure both source and destination plugins have an `includes/PostTypes` folder:
   - If either is missing, warn the user and stop.
2. Verify that each `includes/PostTypes` folder contains at least one post type class:
   - If none are found, warn the user and stop.

**Success criteria:** Both plugins expose PostType classes under `includes/PostTypes`.

### Step 2: CustomFields folder and class in the source plugin

**Do:**

1. In the **source** plugin, check for `includes/CustomFields`:
   - If missing or empty, follow the *creating-custom-fields* skill to create the folder and at least one custom fields class.
2. If there are multiple custom fields classes, ask the user which one should host the liaison field.

**Success criteria:** A target custom fields class exists (or will be created via the skill).

### Step 3: Meta definitions hook

**Do:**

1. Locate the chosen custom fields class.
2. Ensure it has a `specifyMetaDefinitions()` method that:
   - Uses `setMetaDefinitions()` with an array wrapped in a filter.
3. Identify the filter name used in that method; this filter will be used by the liaison hook.

**Success criteria:** Filter name for meta definitions is known.

### Step 4: Create the liaison hook

**Do:**

1. In the **source** plugin, create a hook class using the *creating-a-new-hook* skill:
   - Place it in `includes/Hooks`.
   - Wire it to the filter found in the custom fields class’s `specifyMetaDefinitions()` method.
2. In the hook class:
   - Add a public method that receives the meta definitions array.
   - Add a liaison field entry to that array.
   - Return the modified array.
3. Use a naming pattern for the method like `add<SourceCPTName>LiaisonField` (e.g. `addCampusLiaisonField`).

Example callback structure:

```php
public function addCampusLiaisonField(array $metaDefinitions): array
{
    $metaDefinitions['campus_liaison'] = [$this, 'getCampusLiaisonField'];
    return $metaDefinitions;
}
```

The callable points to another method that will build the actual select field.

**Success criteria:** Hook filters meta definitions and registers the liaison entry.

### Step 5: Domain service and repository

**Do:**

1. In the source plugin, check for an `includes/Repositories` folder:
   - If missing or empty, ask the user if they want to follow the *creating-a-post-repository* skill to create it.
2. Run **creating-a-domain-service** to scaffold `{Liaison}Service` (e.g. `BuildCampusLiaisonFieldService`):
   - `{Liaison}Request` carries domain input (meta key, label, field options as needed).
   - `{Liaison}Response` extends `AbstractResponse` and exposes the built `SelectField` (or `null`) via a getter.
   - `{Liaison}Service::handle()` uses `PostRepository` to list source posts and builds the `SelectField`.
   - Register the service on the plugin manager with a named constant.
3. Implement `handle()`, infer idempotence, and generate unit tests per [idempotent-service-testing.md](../../references/idempotent-service-testing.md).

**Success criteria:** `{Liaison}Service` is registered and returns a `SelectField` from repository data.

### Step 6: Liaison hook (channel adapter)

**Do:**

1. Create the hook via *creating-a-new-hook* (transactional branch) in `includes/Hooks`.
2. Wire it to the meta definitions filter from Step 3.
3. In the hook:
   - Add a `const` for the liaison meta key (e.g. `const CAMPUS_TRAINING_META_KEY = 'campus_liaison';`).
   - In `addCampusLiaisonField()`, register the liaison entry pointing to a factory method.
   - The factory method builds `{Liaison}Request`, calls `{Liaison}Service::handle()`, and returns the field from the response payload (or `null` on failure):

     ```php
     public function getCampusLiaisonField()
     {
         $request = new BuildCampusLiaisonFieldRequest(self::CAMPUS_TRAINING_META_KEY);
         $response = $this->buildCampusLiaisonFieldService->handle($request);

         if (!$response->isSuccess()) {
             return null;
         }

         return $response->getSelectField();
     }
     ```

4. Resolve `{Liaison}Service` in `wire()` via the manager constant — do **not** inject `PostRepository` directly in the hook.

**Success criteria:** Hook registers the meta key and delegates field building to the domain service.

### Step 7: End-to-end shape

**Do:**

Typical layout:

- **Hook:** meta key constant, `register()`, filter callback, factory method delegating to `{Liaison}Service`.
- **Service:** repository access, `SelectField` construction, tests.
- **Request/Response:** channel-agnostic DTOs (no `fromHookContext` / `toHookReturn`).

**Success criteria:** Hook is a channel adapter; business logic lives in `{Liaison}Service`.

### Step 8: Document and ship

**Documentation:**

- In the **source plugin**, update `README.md`:
  - Add or update a “Custom Fields” or similar section.
  - Document the liaison field, its purpose, and which destination post type it links to.
  - If no README exists, create one and add at least a section for custom fields.

**Proposing a commit:**

- Once the liaison field and hook are implemented:
  - Propose a commit summarizing:
    - Creation of the liaison hook.
    - Any repository changes.
    - Documentation updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** Source plugin README documents the liaison; commit proposed.

## Advanced Features

### Example – Campus to Training liaison

User intent:

> \"I want a liaison field on the Training post type so I can associate it with one or more Campus posts.\"

Flow:

1. Destination plugin: the one containing the Training CPT.
2. Source plugin: the one containing the Campus CPT and repository.
3. Ensure both have `includes/PostTypes` and that a Campus repository exists (or create one).
4. In the source plugin:
   - Ensure a suitable custom fields class and meta definitions filter exist.
   - Scaffold `{Liaison}Service` via **creating-a-domain-service**.
   - Create a **service-delegating hook** wired to the filter that delegates field building to the service.
5. Document the new liaison field in the source plugin README.

### Follow-ups

After setting up a liaison field, users may:

- Add more liaison fields connecting other post types.
- Customize the field UI and validation.
- Use liaison relationships in frontend templates or query logic.

Ask which of these they want to handle next and then follow the relevant WonderWP skills (e.g. repositories, block integration, or frontend templates).
