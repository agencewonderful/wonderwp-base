---
name: creating-a-new-block-binding-source
description: >-
  Creates a WonderWP block binding source, including base class, value
  callback, optional integration with repositories and liaison fields, and
  usage patterns. Use when the user wants to expose data to blocks via the
  block bindings API.
metadata:
  domain: wonderwp-block-editor
---

# Creating a new Block Binding source

## Goal

Create a WonderWP block binding source class with a value callback, optional repository and liaison integration, and documented binding JSON patterns.

## Rules

- **Mandatory inputs (for minimal generation):** human-readable label for the source; source identifier as `namespace/key` (e.g. `my-plugin/field`); target plugin.

## Workflow

### Step 1: Base source class

**Do:**

#### Prepare the folder

1. In the plugin, ensure `includes/BlockBindingsSources` exists:
   - If not, create it.

#### Generate the base class

1. Derive a CamelCase class name from the source name plus `Source` suffix (e.g. `MyFieldSource`).
2. Use the plugin’s namespace, with `\BlockBindingsSources` suffix.
3. Extend and implement the correct WonderWP interfaces:
   - Extend `AbstractBlockBindingsSource`.
   - Implement `HasBlockBindingsSourceDefinitionsInterface`.
   - Use `HasBlockBindingsSourceDefinitions` trait.

Copy [assets/BlockBindingsSource.php.tpl](assets/BlockBindingsSource.php.tpl) and adapt namespace, class name, source name, label, and textdomain.

**Success criteria:** Base class lives under `includes/BlockBindingsSources` with correct `provideSourceName`, `provideSourceProperties`, and `getValue` (+ filter).

### Step 2: Extend based on use case

**Do:**

Ask the user which scenario applies:

- **Static source** – return a fixed or computed value.
- **Custom fields or metadata** – value comes from post meta or custom fields.
- **Liaison field** – value derived from a relationship between posts.

#### 2/A: Static source

If the source is static:

- The base class may already be sufficient.
- Adjust `getValue()` to compute the desired value.

#### 2/B: Source for custom fields or metadata

1. Check `includes/CustomFields`:
   - If no custom fields classes exist, warn the user this may not be the intended use.
   - If classes exist, parse their consts to help the user choose which field keys to expose.
2. Check `includes/Repositories`:
   - If none exist, warn the user that retrieving post data may be limited.
   - If multiple repositories exist, ask the user to choose one.
   - Prefer a `PostRepository` or a repository with a `getPostMeta`-like API.
3. Enhance the source class from [assets/BlockBindingsSourceMeta.php.tpl](assets/BlockBindingsSourceMeta.php.tpl):
   - Implement `ManagerAwareInterface` and use `ManagerAwareTrait`.
   - Add `uses_context` to `provideSourceProperties()`.
   - Implement `getValue()` with repository lookup.

#### 2/C: Liaison field-based source

1. Ask the user for the path to the liaison hook file (e.g. `path/to/plugin/includes/Hooks/MyLiaisonField.php`).
2. Verify the file exists and inspect it to understand the liaison meta key and mapping logic.
3. Integrate the liaison logic with the source:
   - Follow steps analogous to 2/B to obtain the post.
   - Use repository or liaison-specific methods to compute the value.
   - Pass the post as a sixth parameter to the filter.
4. In the liaison hook class, add the filter callback from [assets/LiaisonHookFilter.php.tpl](assets/LiaisonHookFilter.php.tpl).

**Success criteria:** Chosen variant (static, meta, or liaison) is implemented with matching filter arity and PHPDoc examples.

### Step 3: Registration

**Do:**

- Sources stored under `includes/BlockBindingsSources` are generally auto-discovered; no manual registration is required.

**Success criteria:** Class path matches project auto-discovery conventions.

### Step 4: Document and ship

**Documentation:**

- In the plugin’s README:
  - Add or update a “Block Bindings Sources” section.
  - Document:
    - Source label and identifier.
    - Any special behavior (e.g. static, custom-fields-based, liaison-based).
    - Example JSON snippets showing how to bind attributes.

**Proposing a commit:**

- Once the source is implemented and tested:
  - Propose a commit summarizing:
    - The new source class.
    - Any repository and liaison integrations.
    - README updates.
  - Provide the matching `git commit` command.

**Success criteria:** README documents source id, behavior, and JSON examples; commit summarizes class, integrations, README.

## Advanced Features

### Example – Custom field binding source

User intent:

> \"Expose a `telephone` custom field as a block binding source.\" 

Flow:

1. Create a source class from [assets/BlockBindingsSourceMeta.php.tpl](assets/BlockBindingsSourceMeta.php.tpl).
2. Wire it to use a `PostRepository` and `getPostMeta()` for the `telephone` meta key.
3. Document usage:

```json
{"metadata":{"bindings":{"content":{"source":"my-plugin/meta-field","args":{"key":"telephone"}}}}}
```

### Follow-ups

After creating a block binding source, users may:

- Add more sources for other fields or relationships.
- Improve editor UX by pairing bindings with block variations or extensions.

Ask what data they want to expose next and replicate this pattern.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/BlockBindingsSource.php.tpl](assets/BlockBindingsSource.php.tpl) | Base static block binding source |
| Template | [assets/BlockBindingsSourceMeta.php.tpl](assets/BlockBindingsSourceMeta.php.tpl) | Meta/custom-field source with repository |
| Template | [assets/LiaisonHookFilter.php.tpl](assets/LiaisonHookFilter.php.tpl) | Liaison hook filter callback |
