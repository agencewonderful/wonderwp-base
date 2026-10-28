---
name: creating-a-new-wp-cli-console-command
description: >-
  Creates a new WonderWP WP-CLI console command class extending
  AbstractWpCliCommand, including folder, namespace, signature, arguments,
  and basic implementation. Use when the user wants to add a CLI command
  within a specific plugin.
metadata:
  domain: wonderwp-backend
---

# Creating a WP-CLI console command

## Goal

Add a simple WonderWP WP-CLI command class extending `AbstractWpCliCommand` in a plugin’s `includes/Commands` folder with signature, synopsis, and `__invoke` implementation.

## Rules

- **Mandatory inputs:** target plugin (where the command will live); purpose of the command (what it should do).
- **Optional:** command arguments—ask whether positional or named (assoc); for each: parameter name, purpose, positional vs assoc (default assoc), required vs optional (default optional).
- Commands are automatically registered by WonderWP when the plugin is run, as long as the class lives in `includes/Commands`.
- Ensure Composer autoload is up to date if you add new classes/namespaces.

## Workflow

### Step 1: Prepare the folder and namespace

**Do:**

1. Determine the plugin folder.
2. Check for an `includes/Commands` folder:
   - If it does not exist, create it.
3. Determine the PHP namespace based on the plugin (e.g. `MyPluginNamespace\Commands`).

**Success criteria:** `includes/Commands` exists and namespace is chosen.

### Step 2: Generate the command class

**Do:**

Create a command class in `includes/Commands`:

- Class name: CamelCase derived from the command purpose (e.g. `MyCustomCommand`).
- Extend `WonderWp\Component\Task\Definition\AbstractWpCliCommand`.

Use this template as a starting point:

```php
<?php

namespace MyPluginNamespace\Commands;

use WonderWp\Component\Task\Definition\AbstractWpCliCommand;

class MyCustomCommand extends AbstractWpCliCommand
{
    public static function getName(): string
    {
        return 'my-plugin-textdomain:my-command-name';
    }

    public static function getArgsDefinition(): array
    {
        return [
            'shortdesc' => 'My command description',
            'synopsis' => []
        ];
    }

    public function __invoke(array $args = [], array $assocArgs = [])
    {
        \WP_CLI::line(sprintf('Hello from %s command!', self::getName()));
    }
}
```

Adjust:

- `getName()` to the desired signature (`plugin-slug:command-name`).
- `shortdesc` based on the user-provided purpose.

**Success criteria:** Command class exists with correct `getName()` and baseline `__invoke()`.

### Step 3: Add command arguments (if needed)

**Do:**

If the user specified arguments:

1. Add a class constant for each argument key:

```php
const MY_FIRST_ARGUMENT_CONST = 'myfirstargumentkey';
```

2. Override `getArgsDefinition()` to define the arguments in `synopsis`:

```php
public static function getArgsDefinition(): array
{
    return [
        'shortdesc' => 'My command description',
        'synopsis' => [
            [
                'name' => self::MY_FIRST_ARGUMENT_CONST,
                'description' => 'My argument description',
                'type' => 'assoc',      // or 'positional', based on user choice
                'optional' => true,     // or false, based on user choice
            ],
        ],
    ];
}
```

3. Use the arguments inside `__invoke()` by reading from `$args` or `$assocArgs`.

**Success criteria:** Synopsis matches agreed argument shapes; `__invoke` reads them correctly.

### Step 4: Document and ship

**Documentation:**

- Look for a `README.md` in the plugin.
- If found, look for a “Commands” section:
  - If present, add a line documenting the new command and its usage.
  - If absent, create a “Commands” section and describe:
    - Command signature.
    - Brief purpose.
    - Arguments (if any).

**Proposing a commit:**

- Once the command is implemented and tested:
  - Propose a commit summarizing:
    - The new command class.
    - Any README updates.
  - Provide the corresponding `git commit` command.

**Success criteria:** README documents the command; commit proposed.

## Advanced Features

### Example – Simple greeting command

User intent:

> \"I want a CLI command that prints a greeting for my plugin.\"

Flow:

1. Target plugin: e.g. `Actu`.
2. Create `includes/Commands/ActuGreetCommand.php` extending `AbstractWpCliCommand`.
3. Set `getName()` to `wwp-actu:greet`.
4. Implement `getArgsDefinition()` with a short description and optional `name` argument if desired.
5. Implement `__invoke()` to print a greeting using `\WP_CLI::line()`.
6. Document the command in the plugin README.

### Follow-ups

For production-grade commands, consider:

- Adding logging, bootstrap, and execution-time tracking patterns from the more advanced *creating-a-wp-cli-command* skill.
- Implementing dry-run modes and richer argument validation.

Use that advanced skill when you need a fully instrumented CLI command.
