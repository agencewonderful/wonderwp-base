---
name: creating-a-new-hook
description: >-
  Creates a WonderWP hook class in a plugin as a simple inline callback or a
  service-delegating adapter (Request/Service/Response). Use when the user wants
  to introduce a new hook-based behavior in a WonderWP plugin.
metadata:
  domain: wonderwp-backend
---

# Creating a Hook

## Goal

Create a WonderWP hook class in `includes/Hooks` implementing `RegistrableInterface` and `HasHookManagerInterface` — either as a **simple hook** (inline glue) or a **service-delegating hook** (adapter to `{Action}Service::handle()`).

## Hook types (terminology)

| Type | When | Template |
|------|------|----------|
| **Simple hook** | ≤ ~10 lines, no I/O, no business delegation | [SimpleHook.php.tpl](assets/SimpleHook.php.tpl) |
| **Service-delegating hook** | Transactional logic; maps hook context → Request → Service → Response | [ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl) |

Avoid the word **thin** in class or file names — in English it suggests lightweight inline glue (simple hook), not delegation.

## Rules

- **Mandatory:** target plugin; hook purpose (what it should do or modify).
- **Optional:** specific WordPress filter or action hook name.
- **Before coding:** run the complexity gate in [service-delegation-architecture.md](../../references/service-delegation-architecture.md) and state **simple** vs **transactional** (service-delegating) in the agent response.
- Hooks in `includes/Hooks` are auto-discovered when the plugin loads — no manual WordPress registration outside the class.
- **Transactional / service-delegating hooks:** use **creating-a-domain-service** first, then [ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl). Mapping hook params → Request and Response → return stays in the hook callback — never on Request/Response DTOs.
- Do **not** use vague heuristics ("try to feel"); use the gate checklist.

## Workflow

### Step 0: Classify complexity (blocking)

**Do:**

1. Read [service-delegation-architecture.md](../../references/service-delegation-architecture.md) gate checklist.
2. Choose **simple** or **transactional** (service-delegating) and document why.

**Branch A — Simple:** continue to Step 1 with [SimpleHook.php.tpl](assets/SimpleHook.php.tpl); skip domain service.

**Branch B — Transactional (service-delegating):**

1. Run **creating-a-domain-service** for `{Action}Request`, `{Action}Response`, `{Action}Service`, manager registration, `handle()`, and tests.
2. Continue to Step 1 with [ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl).

**Success criteria:** Classification documented; service-delegating path includes domain service before hook adapter.

### Step 1: Prepare the folder and namespace

**Do:**

1. Determine the target plugin folder.
2. Create `includes/Hooks` if missing.
3. Use namespace `{PluginNamespace}\Hooks`.

**Success criteria:** `includes/Hooks` exists with correct namespace.

### Step 2: Generate the hook class

**Do:**

**Simple hook:**

1. Copy [assets/SimpleHook.php.tpl](assets/SimpleHook.php.tpl).
2. Implement `RegistrableInterface`, `HasHookManagerInterface`, `HasHookManager` trait only.
3. Do **not** add `wire()` or `ManagerAwareTrait` unless a dependency is truly required for ≤10 lines of glue.
4. Keep callback logic inline (array glue, `__()`, early return).

**Service-delegating hook:**

1. Copy [assets/ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl).
2. Use `ManagerAwareInterface` + `ManagerAwareTrait`; call `wire()` in `register()`.
3. Callback: build `{Action}Request` from hook params → `$service->handle($request)` → map `{Action}Response` to WordPress return (see reference channel mapping table).

**Success criteria:** Class compiles; service-delegating hook contains no business logic beyond mapping.

### Step 3: Wire WordPress hooks

**Do:**

1. Add `addFilter` / `addAction` lines in `register()` for each requested hook.
2. One public callback method per hook; single concern per method.

**Success criteria:** All requested hooks registered.

### Step 4: Document and ship

**Documentation:**

- Update plugin `README.md` **Hooks** section with class name, WordPress hook(s), and **simple** vs **service-delegating** note.
- Service-delegating hooks: mention linked `{Action}Service` constant.

**Proposing a commit:**

- Propose commit with hook class (+ domain service if service-delegating) and docs.

**Success criteria:** README updated; commit proposed.

## Anti-patterns

See [service-delegation-architecture.md](../../references/service-delegation-architecture.md#anti-patterns):

- Repository/API logic inside hook callback
- `fromHookContext()` on Request or `toHookReturn()` on Response
- Heavy logic in `{Plugin}HookService` instead of domain service
- Naming a service-delegating adapter "ThinHook" (ambiguous with simple inline hooks)

## Advanced Features

### Example – Simple: custom block category

1. Classify as **simple**.
2. Create `DeclareBlockCategory` from [SimpleHook.php.tpl](assets/SimpleHook.php.tpl) with inline `addBlockCategory()`.
3. Document in README.

### Example – Service-delegating: third-party sync filter

1. Classify as **transactional**.
2. Run **creating-a-domain-service** for `SyncExternalCatalog*`.
3. Create `[Action]Hook` from [ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl); map filter params to Request and Response payload to return array.
4. Document hook + service in README.

### Follow-ups

- Additional callbacks on the same hook class
- WP-CLI entry point reusing the same service (**creating-a-wp-cli-command**)
- Custom fields, taxonomies, blocks (steer to appropriate WonderWP skills)

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Reference | [service-delegation-architecture.md](../../references/service-delegation-architecture.md) | Gate, contracts, channel mapping |
| Skill | [creating-a-domain-service](../creating-a-domain-service/SKILL.md) | Request/Service/Response + tests |
| Template | [assets/SimpleHook.php.tpl](assets/SimpleHook.php.tpl) | Simple inline hook |
| Template | [assets/ServiceDelegatingHook.php.tpl](assets/ServiceDelegatingHook.php.tpl) | Service-delegating hook adapter |
