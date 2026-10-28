---
name: creating-a-wp-cli-command
description: >-
  Creates a fully instrumented WonderWP WP-CLI command, including logging,
  bootstrap, execution-time tracking, optional dry-run support, and argument
  definitions. Use when the user wants a production-grade WP-CLI command
  rather than a simple console command.
metadata:
  domain: wonderwp-backend
---

# Creating a WP-CLI command

## Goal

Create a production-grade WonderWP WP-CLI command with mandatory `LoggerInterface` / `WpCliLogger`, bootstrap, execution-time logging, and optional dry-run support.

## Rules

- Gather the following from the user.
- **Mandatory:** target plugin where the command should be created; command name (signature suffix, e.g. `my-command`); command description (short human-readable explanation); command type (generic vs importer command).
- If the command is an **importer command**, redirect to the *scaffolding-an-importer-architecture* skill rather than using this generic flow.
- Every WP-CLI command built with this skill must implement the structured logging pattern below.
- **Never** use `\WP_CLI` directly; always route output and errors through the logger.

## Workflow

### Step 1: Folder and class setup

**Do:**

1. The command should live in the plugin’s `includes/Commands` folder:
   - If missing, create `includes/Commands`.
2. Class name:
   - Use CamelCase based on the command purpose (e.g. `MyPluginCommand`).
3. Namespace:
   - Derived from the plugin, e.g. `WonderWp\Plugin\Actu\Child\Commands`.
4. Base class:
   - Extend `WonderWp\Component\Task\Definition\AbstractWpCliCommand`.

**Success criteria:** Path and class skeleton match plugin conventions.

### Step 2: Logging setup (mandatory)

**Do:**

1. Add a `LoggerInterface` attribute:

```php
protected LoggerInterface $logger;
```

2. Import logging classes:

```php
use WonderWp\Component\Logging\LoggerInterface;
use WonderWp\Component\Logging\WpCliLogger;
```

3. Initialize the logger, typically in a `bootstrap()` method (see below), but at minimum:

```php
$this->logger = new WpCliLogger();
```

4. Log command execution at the start of `__invoke()`:

```php
$this->logger->info('Executing command-name with args', ['args' => $args, 'assocArgs' => $assoc_args]);
```

5. Throughout the command, use the logger for:
   - Informational events (`info`).
   - Errors (`error`).
   - Success messages (`success`).

**Never** use `\WP_CLI` directly; always route output and errors through the logger.

**Success criteria:** Logger is property-injected/initialized; all output goes through logger methods.

### Step 3: Command signature and arguments

**Do:**

- Implement `public static function getName(): string`:
  - It returns the command signature.
  - The signature uses two parts separated by `:`:
    - First part: plugin folder slug (e.g. `wwp-actu`).
    - Second part: command name (e.g. `my-command`).

Examples:

- `wwp-actu:fixtures:generate`
- `wwp-cache:clear`
- `wwp-gutenberg-utils:list-blocks`

Implement `public static function getArgsDefinition(): array` to provide:

- `shortdesc`: short description, required if the user supplied one.
- `synopsis`: argument definitions if the command has arguments.

Each argument in `synopsis`:

- `name`: argument name.
- `description`: explanation of the argument.
- `type`: `positional` or `assoc`.
- `optional`: boolean.

**Success criteria:** `getName()` and `getArgsDefinition()` match the agreed CLI contract.

### Step 4: Bootstrap and execution

**Do:**

Implement the main entrypoint with start/end timing and a `bootstrap()` method. See the full structure in [assets/GenericWpCliCommand.php.tpl](assets/GenericWpCliCommand.php.tpl).

Always call `bootstrap()` at the beginning of `__invoke()` before any business logic.

**Domain service reuse:** when the command triggers the same business action as a hook or HTTP endpoint, resolve `{Action}Service` from the plugin manager, build `{Action}Request` **inside the command** from `$args` / `$assoc_args`, call `handle()`, and map `{Action}Response` to logger `success()` / `error()` — do **not** duplicate service logic. See [service-delegation-architecture.md](../references/service-delegation-architecture.md).

**Success criteria:** `bootstrap()` runs first; business logic delegates to `{Action}Service` when applicable.

### Step 5: Execution time tracking

**Do:**

Track execution time for performance insights:

1. At the top of `__invoke()`, record the start time with `microtime(true)`.
2. At the end of `__invoke()`, compute and log the duration.
3. In error paths, also log execution time in error messages.

Ensure both success and failure paths log timing data.

**Success criteria:** Success and error paths include execution time in log output.

### Step 6: Optional dry run support

**Do:**

If the command needs a dry-run mode:

1. Implement and use WonderWP dry-run traits:

```php
use WonderWp\Component\Task\Traits\HasDryRunInterface;
use WonderWp\Component\Task\Traits\HasDryRun;
use WonderWp\Component\Task\Traits\HasDryRunArg;

class MyCommand extends AbstractWpCliCommand implements HasDryRunInterface
{
    use HasDryRun;
    use HasDryRunArg;
```

2. In `getArgsDefinition()`, add the dry-run argument:

```php
public static function getArgsDefinition(): array
{
    return [
        'shortdesc' => '...',
        'synopsis' => [
            self::getDryRunArgDefinition(),
            // other arguments...
        ],
    ];
}
```

3. In `__invoke()`, set and check dry-run mode:

```php
$this->setDryRun(isset($assocArgs[self::DRY_RUN_ARG]));

if ($this->isDryRun()) {
    $this->logger->info("DRY RUN MODE - No changes will be made");
}
```

Guard any mutating actions with an `isDryRun()` check.

**Success criteria:** Dry-run flag is documented in synopsis and respected in mutating code paths.

### Step 7: Document and ship

**Documentation:**

- In the plugin’s `README.md`:
  - Add or update a “Commands” section.
  - Document:
    - Command signature.
    - Description.
    - Arguments and flags (including dry run if available).

**Proposing a commit:**

- Once the command is implemented and tested:
  - Propose a commit summarizing:
    - New command class.
    - Logging and bootstrap patterns.
    - README changes.
  - Provide the corresponding `git commit` command.

**Success criteria:** README matches command behavior; commit message covers implementation and docs.

## Advanced Features

### Example – Generic logged command

User intent:

> \"Create a logged WP-CLI command for my plugin.\"

Flow:

1. Copy [assets/GenericWpCliCommand.php.tpl](assets/GenericWpCliCommand.php.tpl) to `includes/Commands/<CommandName>.php`.
2. Adapt namespace, class name, command signature, and argument definitions.
3. Add command logic between bootstrap and success logging.
4. Document in the plugin README.

### Follow-ups

Once this baseline is in place, users can:

- Add importer-specific logic (via the importer architecture skill).
- Introduce more complex argument handling, interactive prompts, or multi-step workflows.

Ask how they want to extend the command and apply the appropriate WonderWP or project-level patterns.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/GenericWpCliCommand.php.tpl](assets/GenericWpCliCommand.php.tpl) | Full logged WP-CLI command example |
| Reference | [service-delegation-architecture.md](../references/service-delegation-architecture.md) | Reuse `{Action}Service` from CLI entry points |
