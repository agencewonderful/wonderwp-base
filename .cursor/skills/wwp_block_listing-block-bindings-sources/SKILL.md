---
name: listing-block-bindings-sources
description: >-
  Lists available block binding sources in a plugin by inspecting
  BlockBindingsSources classes and their documentation, then presents usage
  details. Use when the user wants to see what block binding sources exist and
  how to use them.
metadata:
  domain: wonderwp-block-editor
---

# Listing Block Bindings sources

## Goal

Inventory block binding sources in a target plugin by inspecting `BlockBindingsSources` classes and PHPDoc, then report identifiers, behavior, and usage JSON—including when `core/post-meta` is enough.

## Rules

- **Mandatory input:** target plugin where sources should be listed.
- This skill is read/analysis oriented and typically does not introduce new files; however, you may suggest documentation improvements (see final workflow step).

## Workflow

> Use this skill when the user asks which block binding sources are available in a plugin and how to use them.

### Step 1: Locate Block Bindings sources

**Do:**

1. In the target plugin, check for an `includes/BlockBindingsSources` folder:
   - If it does not exist, inform the user that no block binding sources are present in this plugin.
   - If it exists, list all PHP files in the folder.
2. For each file:
   - Inspect the class to confirm it is a block binding source (e.g. extends `AbstractBlockBindingsSource`).
   - Look for PHPDoc comments describing:
     - Source label.
     - Source identifier (`namespace/key`).
     - Example usage for JSON bindings.
   - If no documentation is found:
     - Warn the user that this particular binding source is not documented.

**Success criteria:** You know which files are binding sources and which lack PHPDoc.

### Step 2: Extract information

**Do:**

For each well-documented source, collect:

- **Source label** – human-friendly name.
- **Source identifier** – `namespace/key`.
- **Plugin name** (or plugin slug).
- **Behavior/treatment** – whether `getValue()` performs any transformation beyond direct meta lookup:
  - If `getValue()` only returns a post meta value (or similar straight-through field),
    - Note that the native WordPress `core/post-meta` binding might be a simpler alternative.

**Success criteria:** For each documented source, label, identifier, and behavior notes are captured.

### Step 3: Evaluate custom vs native bindings

**Do:**

When a custom source merely proxies a meta field:

- Explain that the user could often use:

```json
{"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"my-meta-key"}}}}}
```

instead of a custom source like:

```json
{"metadata":{"bindings":{"content":{"source":"plugin-namespace/meta-source","args":{"key":"my-meta-key"}}}}}
```

Highlight:

- Custom sources are best when they encapsulate non-trivial logic:
  - Combining fields.
  - Formatting.
  - Liaison-based lookups.
  - Context-aware behavior.

**Success criteria:** The user understands when to prefer `core/post-meta` vs a custom source.

### Step 4: Present the list to the user

**Do:**

1. Present a table or list of block binding sources including:
   - Label.
   - Identifier.
   - Short description of behavior (if available).
   - Notes about whether they do more than direct meta retrieval.
2. When the user is interested in a specific source:
   - Show example binding JSON they can use in a block’s configuration (in one-line format), e.g.:

```json
{"metadata":{"bindings":{"content":{"source":"my-source-namespace/my-source-key","args":{"key":"my-key-name"}}}}}
```

3. If the source has multiple documented usage patterns (e.g. different `args` keys), show each variant.

**Success criteria:** The user has a clear list/table and copy-pasteable JSON examples per source as documented.

### Step 5: Documentation suggestions (optional)

**Do:**

This skill is read/analysis oriented and typically does not introduce new files; however, you may suggest improvements:

- If a source lacks documentation, recommend:
  - Adding PHPDoc comments describing:
    - Label.
    - Identifier.
    - Example bindings JSON.
    - Any non-trivial behavior.

If the user wants, you can then use the *creating-a-new-block-binding-source* skill to help enrich or refactor that source.

**Success criteria:** Undocumented sources have concrete PHPDoc recommendations; user knows they can use *creating-a-new-block-binding-source* for refactors.

## Advanced Features

### Example – Reporting available sources

User intent:

> \"What block binding sources are available in my ‘Campus’ plugin and how do I use them?\" 

Flow:

1. Inspect `includes/BlockBindingsSources` in the Campus plugin.
2. For each source:
   - Extract label, identifier, and usage snippets from PHPDoc.
3. Present:
   - A table of sources.
   - For each, a one-line JSON example the user can paste into a block configuration.
4. Highlight any sources that simply return meta values and could be replaced by `core/post-meta`.

### Follow-ups

After listing sources, users may:

- Request creation or refactoring of sources to match their needs.
- Pair a chosen binding source with a block variation, style, or extension to improve UX.

Ask which source or outcome they want to pursue next and apply the relevant block editor skills.
