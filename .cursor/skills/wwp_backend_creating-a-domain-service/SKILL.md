---
name: creating-a-domain-service
description: >-
  Scaffolds a WonderWP domain service with Request/Response DTOs, manager registration,
  handle() implementation, and optional PHPUnit tests. Use when hook, CLI, HTTP, or
  integration logic needs a dedicated transactional service with channel-agnostic DTOs.
metadata:
  domain: wonderwp-backend
---

# Creating a domain service

## Goal

Scaffold a channel-agnostic business unit: `{Action}Request`, `{Action}Response` (extends `AbstractResponse`), `{Action}Service`, manager registration, `handle()` implementation, and unit tests.

## Rules

- **Mandatory:** target plugin; business action name (`{Action}`); what `handle()` must accomplish.
- Follow [service-delegation-architecture.md](../../references/service-delegation-architecture.md) — Request/Response must **not** include `fromHookContext`, `toHookReturn`, or other channel methods.
- `{Action}Response` must extend `WonderWp\Component\Response\AbstractResponse` (or implement `ResponseInterface` only if inheritance is impossible).
- Business logic lives in `{Action}Service::handle()` — not in hooks, CLI commands, or controllers.
- Tests are generated **after** `handle()` is implemented. See [idempotent-service-testing.md](../../references/idempotent-service-testing.md).

## Workflow

### Step 1: Collect placeholders

**Do:**

1. Resolve `{Namespace}`, `{Plugin}`, `{Action}`, `{TextDomain}`, `{action-snug}`.
2. Confirm the target plugin folder and `{Plugin}Manager.php` location.

**Success criteria:** Placeholders documented; plugin path known.

### Step 2: Composer dependency

**Do:**

1. Check that `wonderwp/response` is available in the project (`composer.json` / `vendor/wonderwp/response`).
2. If missing, propose:

   ```bash
   composer require wonderwp/response
   ```

Confirm with the user before changing Composer files.

**Success criteria:** `WonderWp\Component\Response\AbstractResponse` is autoloadable.

### Step 3: Create folders and DTOs

**Do:**

1. Ensure folders exist under `includes/`:
   - `Request/`
   - `Response/`
   - `Service/`
2. Create classes from templates:
   - [assets/ActionRequest.php.tpl](assets/ActionRequest.php.tpl)
   - [assets/ActionResponse.php.tpl](assets/ActionResponse.php.tpl)

Adapt properties/getters to the domain. Keep DTOs free of channel mapping.

**Success criteria:** Request and Response classes compile; Response extends `AbstractResponse`.

### Step 4: Create service and register on manager

**Do:**

1. Create `{Action}Service` from [assets/ActionService.php.tpl](assets/ActionService.php.tpl).
2. Register on `{Plugin}Manager` using [assets/ManagerServiceRegistration.php.tpl](assets/ManagerServiceRegistration.php.tpl):
   - Add a named constant (e.g. `{ACTION}_SERVICE`)
   - Inject dependencies via `$this->getService(...)` in the factory closure

**Success criteria:** Service registered; entry points can resolve it via `$this->manager->getService({Plugin}Manager::{ACTION}_SERVICE)`.

### Step 5: Implement handle()

**Do:**

1. Implement full business logic in `{Action}Service::handle()`.
2. Return `{Action}Response` with appropriate `code`, `msgKey`, `textDomain`, and payload getters.
3. On failure: non-2xx code, set error via `setError()` when using exceptions, or encode failure in response contract.

**Success criteria:** `handle()` is complete and callable from any entry point with a `{Action}Request`.

### Step 6: Infer idempotence and generate tests

**Do:**

1. Read `handle()` and infer idempotence (see [idempotent-service-testing.md](../../references/idempotent-service-testing.md)).
2. Ask the user **only** if a serious business ambiguity remains (e.g. re-sync vs no-op).
3. Create `tests/Unit/Service/{Action}ServiceTest.php` from [assets/ActionServiceTest.php.tpl](assets/ActionServiceTest.php.tpl).
4. **Always** implement:
   - `testHandleReturnsExpectedResponseForValidRequest`
   - `testHandleReturnsFailureForInvalidOrImpossibleRequest`
5. If idempotence inferred, uncomment/add replay and sequence tests.
6. Mock every constructor dependency — do not skip tests because the service is coupled.
7. Add extra `testHandle*` methods freely for relevant branches.
8. Run PHPUnit and fix until green:

   ```bash
   ./vendor/bin/phpunit tests/Unit/Service/{Action}ServiceTest.php
   ```

**Success criteria:** Tests pass; failure case covered; idempotence scenarios present when inferred.

### Step 7: Document and ship

**Documentation:**

- Update plugin `README.md` with a **Services** section describing `{Action}Service`, its request/response, and manager constant.
- Do **not** document channel mapping here (hooks/CLI skills own that).

**Proposing a commit:**

- Propose a commit summarizing service, DTOs, manager wiring, and tests.

**Success criteria:** README updated; commit proposed.

## Advanced Features

### Wiring a hook entry point

After this skill completes, use **creating-a-new-hook** (transactional branch) with [ServiceDelegatingHook.php.tpl](../creating-a-new-hook/assets/ServiceDelegatingHook.php.tpl) to map WordPress callback parameters to `{Action}Request` and map `{Action}Response` to the filter/action return.

### Wiring WP-CLI

Use **creating-a-wp-cli-command** to resolve the same `{Action}Service`, build `{Action}Request` from CLI args inside the command, and map `{Action}Response` to `WP_CLI::success()` / `WP_CLI::error()`.

## Resources

| Type | Path | Description |
| ---- | ---- | ----------- |
| Reference | [service-delegation-architecture.md](../../references/service-delegation-architecture.md) | EntryPoint → Request → Service → Response |
| Reference | [idempotent-service-testing.md](../../references/idempotent-service-testing.md) | Test scenarios and mock strategy |
| Template | [assets/ActionRequest.php.tpl](assets/ActionRequest.php.tpl) | Domain request DTO |
| Template | [assets/ActionResponse.php.tpl](assets/ActionResponse.php.tpl) | Response extending AbstractResponse |
| Template | [assets/ActionService.php.tpl](assets/ActionService.php.tpl) | Service with handle() |
| Template | [assets/ManagerServiceRegistration.php.tpl](assets/ManagerServiceRegistration.php.tpl) | Manager constant + addService |
| Template | [assets/ActionServiceTest.php.tpl](assets/ActionServiceTest.php.tpl) | PHPUnit minimum + extensions |
